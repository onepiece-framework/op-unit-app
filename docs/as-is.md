# App Unit As-Is

## Scope

This document describes the current As-Is responsibility and flow of the App unit.

## Primary Responsibility

The App unit is responsible for application flow management after framework startup.

Its responsibility is not:

- deciding routing rules itself
- implementing layout details itself

Its responsibility is:

- obtain the endpoint from the Router unit
- execute the endpoint
- buffer the executed content
- call the Layout unit when later rendering flow requires it

## Current Flow

The current App unit flow is:

1. get the endpoint from `OP()->Unit()->Router()->EndPoint()`
2. execute that endpoint
3. buffer the output content
4. if later rendering conditions require it, call the Layout unit

## Router Boundary

The App unit does not decide which endpoint should run.

That belongs to the Router unit.

The App unit consumes the Router result.

## Layout Boundary

The App unit does not implement layout behavior itself.

Its role is only to call the Layout unit when layout processing should continue.

How the layout is actually performed belongs to the Layout unit.

## Current MIME-Based Decision

In the current implementation, the decision to continue to layout is based on MIME.

This should be understood as an As-Is implementation decision by the unit author.

It is not being documented here as a permanent abstract specification.

The practical intention of the current behavior is to avoid loading unnecessary units and reduce memory use.

## Meaning

The important point is that the App unit manages application flow.

Its responsibility stops at:

- endpoint execution
- buffering output
- handing off to the Layout unit when appropriate

This clear separation keeps:

- routing in Router
- application flow in App
- final layout behavior in Layout

