@core @core_calendar
Feature: Perform basic calendar functionality
  In order to ensure the calendar works as expected
  As an admin
  I need to create calendar data

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
      | student3 | Student | 3 | student3@example.com |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1 | topics |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |
      | student3 | C1 | student |
    And the following "groups" exist:
      | name | course | idnumber |
      | Group 1 | C1 | G1 |
    And the following "group members" exist:
      | user | group |
      | student1 | G1 |
    When I log in as "admin"
    And I am on "Course 1" course homepage with editing mode on
    And I add the "Calendar" block

  Scenario: Create a site event
    And I create a calendar event with form data:
      | Type of event | site |
      | Event title | Really awesome event! |
      | Description | Come join this awesome event, sucka! |
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "This month"
    And I should see "Really awesome event!"
    And I log out
    And I log in as "student2"
    And I follow "This month"
    And I should see "Really awesome event!"

  Scenario: Create a course event
    And I create a calendar event with form data:
      | Type of event | course |
      | Event title | Really awesome event! |
      | Description | Come join this awesome event, sucka! |
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "This month"
    And I should see "Really awesome event!"
    And I log out
    And I log in as "student2"
    And I follow "This month"
    And I should not see "Really awesome event!"

  Scenario: Create a group event
    And I create a calendar event with form data:
      | Type of event | group |
      | Group | Group 1 |
      | Event title | Really awesome event! |
      | Description | Come join this awesome event |
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "This month"
    And I follow "Really awesome event!"
    And "Group 1" "text" should exist in the ".eventlist" "css_element"
    And I log out
    And I log in as "student3"
    And I follow "This month"
    And I should not see "Really awesome event!"

  Scenario: Create a user event
    And I create a calendar event with form data:
      | Type of event | user |
      | Event title | Really awesome event! |
      | Description | Come join this awesome event, sucka! |
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "This month"
    And I should not see "Really awesome event!"

  Scenario: Delete an event
    And I create a calendar event with form data:
      | Type of event | user |
      | Event title | Really awesome event! |
      | Description | Come join this awesome event, sucka! |
    And I click on "Delete event" "link" in the ".event div.commands" "css_element"
    And I click on "Delete" "button"
    And I should not see "Really awesome event!"

  Scenario: Edit an event
    And I create a calendar event with form data:
      | Type of event | user |
      | Event title | Really awesome event! |
      | Description | Come join this awesome event, sucka! |
    And I click on "Edit event" "link" in the ".event div.commands" "css_element"
    And I set the following fields to these values:
      | Event title | Mediocre event :( |
      | Description | Wait, this event isn't that great. |
<<<<<<< HEAD
    And I press "Save changes"
    And I should see "Mediocre event"
=======
    And I press "Save"
    Then I should see "Mediocre event"

  @javascript
  Scenario: Module events editing
    Given I log in as "admin"
    And I am on "Course 1" course homepage with editing mode on
    And the following "activities" exist:
      | activity | course | idnumber | name        | intro                   | timeopen      | timeclose     |
      | choice   | C1     | choice1  | Test choice | Test choice description | ##today## | ##today##  |
    When I follow "This month"
    Then I should see "Test choice opens"
    And I should see "Test choice closes"
    When I click on "Test choice opens" "link"
    Then "Delete" "button" should not exist
    And "Edit" "button" should not exist
    And I should see "Course event"
    When I click on "Go to activity" "link"
    And I wait to be redirected
    Then I should see "Test choice"
    And I am on "Course 1" course homepage
    And I follow "This month"
    When I click on "Test choice closes" "link"
    Then "Delete" "button" should not exist
    And "Edit" "button" should not exist
    And I should see "Course event"
    When I click on "Go to activity" "link"
    And I wait to be redirected
    Then I should see "Test choice"

  @javascript
  Scenario: Attempt to create event without fill required fields should display validation errors
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I follow "This month"
    And I click on "New event" "button"
    When I click on "Save" "button"
    Then I should see "Required"
    And I am on homepage
    And I follow "This month"
    And I click on "New event" "button"
    And I set the field "Type of event" to "Course"
    When I click on "Save" "button"
    Then I should see "Required"
    And I should see "Select a course"
    And I set the field "Event title" to "Really awesome event!"
    When I click on "Save" "button"
    Then I should see "Select a course"
>>>>>>> v3.5.2
