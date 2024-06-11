# Changelog for ReferralHub

This file documents all the significant changes made in each version of the ReferralHub plugin.

## [1.1.7] - 2024-06-10
### Added
- Implemented user role restrictions for creating new Service Types and Locations to enhance system security and data integrity.
- Added new capabilities for "Service Providers" to view, edit, and delete their own media and service posts, enhancing their autonomy and facilitating relevant content management.
- Implemented detailed capabilities for "Service Providers" including the ability to delete services and media they created themselves, ensuring full management capabilities within the plugin.
- Introduced a new settings panel for "Service Providers", centralizing various configuration options and improving usability.
- Added the functionality to upload and manage a panel logo via media library in the settings.
- Introduced a template selection for the frontend style with visual thumbnails for each template option.
- Included new color and font settings for better customization of the inquiry form and result templates.

### Changed
- Refined capability definitions for "Service Providers" to ensure permissions align with intended functionalities and allow for more effective administration of listed services.
- Adjustments in access control methods to ensure that "Service Providers" have appropriate permissions without administrative intervention.
- Relocated referral fee settings into the new settings panel, streamlining configuration processes and improving organizational efficiency.
- Moved the Referral Fee settings to a new tab within the settings page for better organization.
- Enhanced the functionality of the "Save Changes" button to ensure it works consistently across all tabs in the settings page.
- Updated enqueue scripts and styles to ensure compatibility with the latest WordPress standards.

### Fixed
- Fixed bugs related to visibility and edit/delete capabilities of posts and media for "Service Providers", ensuring CRUD operations function as expected.
- Security adjustments and performance optimizations to enhance the stability and reliability of the plugin.
- Fixed deprecated `strpos` and `str_replace` warnings by ensuring non-null inputs in the functions.
- Corrected the handling of color and font settings to update dynamically on the settings page.
- Addressed the `map_meta_cap` notice by ensuring proper registration and capability assignment for the `rhb_service` post type.


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
