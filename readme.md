# Database Considerations.

This project consist of following databases:

users (that contains login information)
survey (that contains survey names and links to users)

list of {
  survey_name (this consist of specific survey question set)
  survey_name_results (this consist of specific survey results)
}

# Flow.

Login system

Create Survey
Using new survey name -> insert questions into survey_name table creates new survey to existing username.
Finish survey
Create new survey -> creates survey_name_results table.

User are presented with links that will do the survey via get request.
