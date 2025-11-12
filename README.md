# Crowdin Base

This library provides basic functionality for other Crowdin libraries:

- Command `crowdin:setup` to write the configuration file with all available projects
- The class `\FriendsOfTYPO3\CrowdinBase\Configuration\ConfigurationReader` which can be used in the other libraries
  to get the list of projects (an array of `\FriendsOfTYPO3\CrowdinBase\Configuration\Entity\Project` objects).

## Configuration

Copy the `.env.example` file to `.env` and configure the required values.

To run the setup call `bin/crowdinSetup`. The configuration file is then stored in the given configuration file path.
