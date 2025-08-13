# Planning Poker

## Overview

This project aims to implement a lightweight planning poker tool to aid in the process of sprint planning. The tool should allow anyone to quickly set up a room, and invite players to join in without having to create accounts.

## Core Features

- No player accounts.
- Limited number of players per room.
- Uses Websockets to communicate state to users in the room.
- Rooms expire after a period of inactivity.

## Running Locally

### Requirements
- Docker
- PHP (8.4)
    - ext-mongodb
- Composer
- Node (22+)

### Steps

1. From the root of the project run `composer install`.
2. Copy `.env.example` to `.env` and populate.
3. Run `sail up -d` to start the docker containers.
4. Run `npm ci`.
5. Run `npm run build`.
6. Run `sail artisan reverb:start` to start the reverb server.
7. Navigate to `http://localhost` in the browser.
