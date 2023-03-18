# Unimplemented (planned for 1.0.0 or later)

- convert config files back to YAML format
- FIX B4 PARSER: remove encoding detection, treat everything as YTF-8, reparse all data!
- code style: create `App\Core` namespace and move inside all contracts and
  classes that do not depend on non-app classes like Symfony components
- code style: try to apply advice from
  <https://simshaun.medium.com/decoupling-your-application-user-from-symfonys-security-user-60fa31b4f7f2>
- use [the official API](https://nexusclash.com/viewtopic.php?f=2&t=3701) to periodically fetch data?

# Implemented (planned for 1.1.0)

- create quick and dirty command to export leaderboards in PHPBB post format

# Version 1.0.0

- create interfaces and classes to represent leaderboard table as seen in game
- create Doctrine entities to persist these leaderboard tables in database
- implement general parser infrastructure to handle stored page views
- implement parser for Breath 4 final leaderboards
- implement a public page for browsing leaderboards
- create and apply basic UI theme/styling, based on Tailwind CSS
- some more internal code cleanups

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
