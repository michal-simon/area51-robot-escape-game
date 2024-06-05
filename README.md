Area 51 Robot Escape Game
--------

[![Codeac.io](https://static.codeac.io/badges/2-810649643.svg "Codeac.io")](https://app.codeac.io/github/michal-simon/area51-robot-escape-game)

## Requirements
- Docker

## Installation and Running Locally

Both installation and running is recommended to do inside a docker container.

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up
```

## Testing

Access port 80 on localhost to run the script: [http://localhost](http://localhost)

## Opportunities for Improvement

- OpenAPI spec validation
- Mock API for local development and integration testing
- Automated tests (unit, integration)
- Continuous Integration - Building of an artifact for deployment
