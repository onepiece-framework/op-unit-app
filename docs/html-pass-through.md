# HTML Pass-Through in `op-unit-app`

## Overview

This document describes only the parts of the current HTML Pass-Through behavior that are handled by `op-unit-app`.

It does not describe Router-side endpoint selection in detail. It starts from the point where the App unit has already received an endpoint.

## Current Responsibility of the App Unit

In the current implementation, the App unit is responsible for:

- receiving the endpoint selected by the Router unit
- determining the MIME type from the endpoint extension
- deciding whether the endpoint should be treated as text or non-text
- executing text endpoints through `OP()->Template()`
- capturing the output before final response emission
- deciding whether layout should be applied

## Current Execution Flow

Once the App unit receives an endpoint, the flow is:

1. read the endpoint from `OP()->Unit()->Router()->EndPoint()`
2. determine the extension from the endpoint path
3. resolve MIME type from that extension
4. if the MIME type is not `text/*`, return the file directly with `file_get_contents()`
5. if the MIME type is text, convert the endpoint to a meta path
6. execute the endpoint through `OP()->Template()`
7. capture the output in the App unit buffer
8. if the final MIME type is `text/html`, call `OP()->Unit()->Layout()->Auto()`
9. otherwise, output the stored content directly

## Meaning in the Current Design

From the App unit point of view, HTML Pass-Through means:

- a text-oriented resource can be executed as an endpoint
- execution result is not emitted immediately
- final output is delayed until the framework decides whether layout is needed

This is the App-side part of the NEW WORLD execution model.

## Non-Text Handling

The App unit does not run non-text endpoints through `OP()->Template()`.

If the resolved MIME type is not `text/*`, the App unit currently returns the file content directly.

This means the App unit draws a practical line between:

- text-oriented pass-through execution
- non-text direct response handling

## [DOC-GAP] Current Terminology Gap

The code comment says `For HTML Pass Through`, but the App unit behavior is already broader than HTML alone.

At the App unit level, the current logic covers text-oriented resources based on MIME handling, not just HTML files.

So the historical term remains, but the actual App unit behavior is already resource-oriented.

## [DOC-GAP] Current Implementation Gap

There is a visible gap between the historical name and the current App unit behavior.

### 1. The name says HTML

But the App unit logic is based on MIME and text/non-text handling.

### 2. The text path is broader than HTML

Any endpoint that resolves to `text/*` is executed through `OP()->Template()` and then goes through the output buffering flow.

### 3. Non-text behavior is different

Non-text resources are not executed through the same path. They are returned directly with `file_get_contents()`.

So the App unit already contains a split behavior inside what is historically called HTML Pass-Through.

## [DOC-FUTURE] Future Direction

The current naming and the current App unit behavior are not fully aligned.

For now, the historical name `HTML Pass-Through` is still used.

However, this mismatch is recognized, and it should be made clearer or resolved more cleanly in future evolution.
