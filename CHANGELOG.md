# Change Log
All notable changes to this project will (in theory) be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [3.0.0] - 2019-03-13
### Changed
- 'mfaVerify' now returns the MFA object
- change 'email' to not required on createUser to support onboarding use case
### Removed
- Removed `spouse_email` from User

## [2.6.0] - 2019-02-04
### Added
- Added support for recovery method endpoints.
- Added `hide` property on `user`
- Added mfaUpdate() (`PUT /mfa/{mfaId}`) to update MFA label.
- Added authenticateNewUser() to authenticate using an invite code.
- Added new MFA type, `manager`, for sending a "rescue" code to user's manager.
- Added 'personal_email' and 'groups' properties on User.
### Changed
- Tighter validation on idBrokerUri

## [2.5.1] - 2018-11-01
### Fixed
- Response from `PUT /user/{employee_id}/password` is now returned from `setPassword` method.

## [2.5.0] - 2018-07-03
### Added
- Can now provide `manager_email` when creating or updating a user.
- Can now provide `spouse_email` when creating or updating a user.

## [2.4.0] - 2017-12-16
### Added
- Use custom exception class to report status code as well.

## [2.3.0] - 2017-11-20
### Added
- Can now specify `require_mfa` (`'yes'` or `'no'`) when creating or updating
  a user.

## [2.2.1] - 2017-11-14
### Fixed
- Updated error message when the list of trusted IP ranges is not an array to
  include what the given trusted IP ranges was (including data type).

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

[Unreleased]: https://github.com/silinternational/idp-id-broker-php-client/compare/3.0.0...HEAD
[3.0.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.6.0...3.0.0
[2.6.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.5.1...2.6.0
[2.5.1]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.5.0...2.5.1
[2.5.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.4.0...2.5.0
[2.4.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.3.0...2.4.0
[2.3.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.2.1...2.3.0
[2.2.1]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/2.1.2...2.2.0
[0.2.1]: https://github.com/silinternational/idp-id-broker-php-client/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/silinternational/idp-id-broker-php-client/compare/071923e...0.1.0
