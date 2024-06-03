# Changelog for ReferralHub

This file documents all the significant changes made in each version of the ReferralHub plugin.

## [1.1.3] - 2024-05-29
### Added
- Introduced a Referral Fee system, allowing site owners to charge Referral Fees for views or approved inquiries.
- Implementation of Global Referral Fee Settings and Specific Referral Fee Settings, allowing Referral Fee configurations at the site level and individually for each Service Provider.
- Added conditional Referral Fee functionality based on the inquiry status (approved or not).

### Fixed
- Fixed the application of Referral Fees, where the view Referral Fee was not calculated correctly when the Referral Fee type was set only for approved inquiries.
- Adjustments in the override logic to ensure that user settings are respected when specified.

## [1.1.2] - 2024-05-25
### Added
- Implemented user creation during the inquiry process.
- Added functionality to allow inquiries without login or user creation.
- Improved AJAX handling and logging for debugging.
- Updated the inquiry form to capture user details and provide login/account creation options.

### Fixed
- Corrected issues where inquiries were not functioning without login.
- Ensured proper handling and logging of the account creation process.

## [1.1.1] - Previous Date
### Added
- Initial implementation of the inquiry functionality.
- Basic user authentication and display of inquiry results.

## [1.1.0] - Initial Release
### Added
- Core functionality for managing Service Providers listings.
- Basic inquiry and filter options.

---

This changelog follows the [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) standards and adheres to [Semantic Versioning](https://semver.org/).

Developed by Ariel Souza
