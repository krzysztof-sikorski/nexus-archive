# Version 0.5.0

- fix crash in token command on empty username input
- create basic admin panel
- convert all config files to PHP format
- general code cleanup and refactoring
- update installed dependencies to newer versions
- update Symfony recipes metadata, port changes to appropriate files
- start using Doctrine Migrations: create initial migration for existing tables

# Version 0.4.0

- remove unnecessary properties from NexusRawData

# Version 0.3.0

- remove NexusRequestLog entity and all related code (form, repository, etc)

# Version 0.2.0

- create new form to handle data format expected from browser extension

# Version 0.1.1

- fix parsing of JSON fields at cost of ignoring JSON syntax errors

# Version 0.1.0

- basic submit form to store request logs for further processing
- primitive authentication via access tokens
- simple console commands to create user accounts and access tokens
