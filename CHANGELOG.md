# Change Log Logger


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
. composer update

### Fixed
- Reinitialize Debugger when init is called
- nullable function parameters typed


## [1.0.3] - 2024-03-04

### Added

- Strict types declaration
- exception handler initiator
- error handler initiator
- more tests
- more documentation

### Changed

- upgrade traits from unit tests
- clean code and use Psr Log Library traits
- Instantiation system
- new lines after traces for separate log lines

### Fixed

- Quality code


## [1.0.2] - 2023-09-30

### Added

- Added .gitattributes in order to clean donwloaded data

### Changed

### Fixed


## [1.0.1] - 2023-09-27

### Added

### Changed

- Reorder log info

### Fixed


## [1.0.0] - 2023-06-20

### Added

- Initial release, first version

### Changed

### Fixed
