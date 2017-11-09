# Change Log
All notable changes to this project will (in theory) be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [2.2.0] - 2017-11-09
### Added
- Throw custom exception type when `mfaVerify` gets a `429 Too Many Requests`
  response, indicating that that MFA ID is currently rate-limited.

## ... sorry, we're missing several here...

## [0.2.1] - 2017-03-14
### Changed
- Fix return types in doc. comments: `\GuzzleHttp\Command\Result` (not `array`).

## [0.2.0] - 2017-03-02
### Added
- Added `findUser()`.

### Removed
- Removed `findUsers()` (because it should have been singular).

## [0.1.0] - 2017-03-01
### Added
- Initial version of ID Broker API client.

[Unreleased]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.2.0...HEAD
[2.2.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.1.2...2.2.0
[0.2.1]: https://github.com/silinternational/idp-id-broker-php-client/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/071923e...0.1.0
