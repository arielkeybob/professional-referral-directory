# Changelog for ReferralHub

This file documents all the significant changes made in each version of the ReferralHub plugin.

## [1.1.6] - 2024-06-02
### Added
- Introduced a new Setup Wizard for an improved onboarding experience.
- Added a submenu link for the Setup Wizard to allow users to access it later.
- Updated various function names, variables, and other identifiers to use the new `rhb` prefix, reflecting the plugin's rebranding to ReferralHub.

### Changed
- Removed the old "pdr-welcome-page" and its submenu.
- Updated terminology in line with the new plugin structure and goals.
- Changed "Service Provider" to "Prestador de Serviços" in the Brazilian Portuguese translation.
- Updated the database schema and options to use the new `rhb` prefix.

### Fixed
- Minor bug fixes and performance improvements.

## [1.1.5] - 2024-06-01
### Changed
- Terminology update: 
  - "Search" to "Inquiry"
  - "Search Date" to "Inquiry Date"
  - "Search Status" to "Inquiry Status"
  - "Commission Value View" to "Referral Fee Value View"
  - "Commission Value Approval" to "Referral Fee Value Agreement Reached"
  - "Approved Inquiry" status to "Agreement Reached"
  - "Approved" status to "Agreement Reached"
- Updated all references to the old terms throughout the plugin's codebase, database, and documentation to reflect the new terminology.

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
