# Change Log - Logger

## [1.1.3] - 2026-01-20

### Added

- Checked full compatibility with php 8.5
- StreamRepository in order to send the composed data to a openned stream

### Changed

- Output buffer, for screen repository, use cast for stringable objects instead to use a vardump

### Fixed

- ScreenRepository add PRE tag in order to format output buffer when NO CLI SAPI is used

## [1.1.2] - 2025-07-29

### Added

### Changed

- fix composer stability

### Fixed

## [1.1.1] - 2025-06-07

### Added

### Changed

- 0766 permission in order to access to logs folder
- Changed composer support from php v8.1

### Fixed

- Removed LOCK_EX for FileRepository, because it has been tagged as deprecated

## [1.1.0] - 2024-12-12

### Added

- Use Handlers on Logger constructor in order to can save into files or databases
- Use Composers on Handlers in order to configure distincts message formats
- Added Stringable support for message variables translation
- SET and GET methods into Debugger in order to simplify the Logger operations
- init() method in order to use Debugger class as singleton
- check php 8.4 compatibility

### Changed

- Change PlainText to TextComposer for basic log text
- File log permissions
- Moved time mark from repository to composer
- setLogger require a LoggerInterface
- unified error and exceptions initiators functions
- composer update

### Fixed

- Reinitialize Debugger when init is called
- nullable function parameters typed
