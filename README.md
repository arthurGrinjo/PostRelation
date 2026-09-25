### Main branch

Unable to deserialize IRI to User object in Activity POST - Reproduction repository.

Installation:

• Install ddev. (https://ddev.com/)

• Clone the repository.

• `ddev start` (inside project directory)

• `ddev composer install`

• `ddev console d:m:m`

• `ddev console d:f:l`

• `ddev describe` (to figure out where project is running)

• project running in https://post-relation.ddev.site/

All Operations (GET/POST/PUT/DELETE) work as expected. However, unable to POST an IRI and deserialize it, see ActivityRequestDto.
Note: Solution should not break the working Operations of course :) And cache sometimes really is a pain in the ass..

Thoughts:

• Annotate User property with Map()
• Use DecorateObjectMapper, however still not getting in there..
• Set map: false/true in the Post Operation (ApiResource\Activity.php)
• ...

(running out of ideas.. And not understanding why $data in the StandardProcessor has an User object, however without the correct Uuid)

### Test branch

• `git checkout phpunit`

• Clone the repository.

• `ddev console d:d:c`

• `ddev console d:m:m`

• `ddev describe` (to figure out where project is running)
