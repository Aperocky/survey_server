# Database Considerations.

This project consist of following databases:

users (that contains login information)
surveys (that contains survey names and links to users)

list of {
  survey_name (this consist of specific survey question set)
  survey_name_results (this consist of specific survey results)
}

# Flow.

Login system

Create Survey
Using new survey name -> insert questions into survey_name table.
Finish survey
Create new survey -> update surveys table for user, creates survey_name_results table.

User exit -> session timeout -> keep current survey_name table, start user from last question.

User are presented with links that will do the survey via get request. 
