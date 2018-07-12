<<<<<<< HEAD:checkdeadline/tests/behat/installed.feature
@mod @mod_checkdeadline
=======
@mod @mod_checkdeadline
>>>>>>> checkdeadline:source/tests/behat/installed.feature
Feature: Installation succeeds
  In order to use this plugin
  As a user
  I need the installation to work

  Scenario: Check the Plugins overview for the name of this plugin
    Given I log in as "admin"
    And I navigate to "Plugins overview" node in "Site administration > Plugins"
    Then the following should exist in the "plugins-control-panel" table:
        |Plugin name|
<<<<<<< HEAD:checkdeadline/tests/behat/installed.feature
        |mod_checkdeadline|
=======
        |mod_checkdeadline|
>>>>>>> checkdeadline:source/tests/behat/installed.feature
