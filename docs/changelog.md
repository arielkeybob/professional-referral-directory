# Changelog for ProfessionalDirectory

This file documents all the significant changes made in each version of the ProfessionalDirectory plugin.

## [1.1.3] - 2024-05-29
### Added
- Introduced a commission system, allowing site owners to charge commissions for views or approved searches.
- Implementation of Global Commission Settings and Specific Commission Settings, allowing commission configurations at the site level and individually for each professional.
- Added conditional commission functionality based on the search status (approved or not).

### Fixed
- Fixed the application of commissions, where the view commission was not calculated correctly when the commission type was set only for approved searches.
- Adjustments in the override logic to ensure that user settings are respected when specified.

## [1.1.2] - 2024-05-25
### Added
- Implemented user creation during the search process.
- Added functionality to allow searches without login or user creation.
- Improved AJAX handling and logging for debugging.
- Updated the search form to capture user details and provide login/account creation options.

### Fixed
- Corrected issues where searches were not functioning without login.
- Ensured proper handling and logging of the account creation process.

## [1.1.1] - Previous Date
### Added
- Initial implementation of the search functionality.
- Basic user authentication and display of search results.

## [1.1.0] - Initial Release
### Added
- Core functionality for managing professional service listings.
- Basic search and filter options.

---

This changelog follows the [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) standards and adheres to [Semantic Versioning](https://semver.org/).

Developed by Ariel Souza
