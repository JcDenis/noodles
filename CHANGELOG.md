dev
- [ ] add cache system
- [ ] add option for gravatar built in unknow avatar @see https://fr.gravatar.com/site/implement/images/

1.0 - 2023.05.05
- require dotclear 2.26
- require php 8.1+
- use namespace
- fix phpstan level 9 warnings
- use short settings names (lost old settings)
- use json rather than serialize (lost old settings)
- use only one default avatar
- add plugin UNinstaller features

0.9 - 2022.11.20
- fix compatibility with Dotclear 2.24 (required)

0.8 - 2021.10.30
- use url handler for dynamic js and css
- add libravatar API

0.7 - 2021.09.09
- fix image directories
- fix Dotclear 2.19 compatibility
- enhance code style
- update translation
- update license
- update images

0.6.1 - 2010.07.05
- Fixed lowercase email (tks Damien Clauzel)
- Fixed a stupid error on admin page

0.6 - 2010.06.05
- Switched to DC 2.2
- Fixed javascript encode URI
- Fixed image paths and urls

0.5 - 2009.09.23
- Fixed DC 2.1.6 URL handlers ending
- Fixed image size in js
- Removed ''url prefix'' settings as myUrlHandlers does it perfectly
- Added _uninstall.php feature