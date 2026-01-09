# Workflow

- Always start a session by addressing me in a friendly way. My name is Richard!
- Never use emojis in code, READMEs, or any files that will be committed, pushed, and deployed
  - Emojis are OK in branch notes (dev_assets/) for internal documentation
- Never issue any git commands; instead, just tell me which command to run
- Never issue any npm commands; instead, just tell me which command to run
- Never issue any composer commands; instead, just tell me which command to run
- At the start of each session, please review the files within ./dev_assets
  overview.md in order to get up to speed on the project.
- All dev notes files reside in ./dev_assets/

# Branch-based development

- At the start of each session, after our greeting, please ask me which branch
  we will be working on. When I inform you, please look in dev_assets for an md
  file whose name matches the branch name. 
- If you don't find the md file in the root of dev_assets, look for a directory
  by that name. If that exists, please look in there for the md file. Also
  review any other files that are in that directory.
- If a directory does not exist whose name matches the branch name, please 
  create it and move the file into it if it exists.
- If the file doesn't exist, please create it inside the directory of the same name. 
- If the file does exist, please read it thoroughly and understand it completely.
- Append dated entries at the end of the md file in YYYY-MM-DD HH:MM format
  Precede each heading with a blank line.
- **IMPORTANT: After each and every exchange with Richard, log what was discussed
  to the branch-specific md file.** This includes:
  - What questions or requests Richard made
  - Any findings, discoveries, or analysis you performed
  - Suggestions or recommendations you provided
  - Code changes made
  - Commands to run
  - Decisions made
  - Any relevant context for future sessions

# Cleaning HTML Conversation Exports

When I provide you with an HTML file exported from a Claude conversation and ask
you to clean it, follow this process:

1. **Locate the file**: Look for it in dev_assets/ or a branch-specific
   subdirectory
2. **Clean the HTML**: Use a Python script to:
   - Remove all HTML tags, scripts, and styles
   - Extract the actual conversation content
   - Preserve code blocks as markdown code blocks (triple backticks)
   - Convert inline code to markdown inline code (single backticks)
   - Format with clear User/Assistant separators
3. **Create output**: Write to a file replacing `.html` with `.md` suffix in the
   same directory
4. **Add header**: Include:
   - Title describing the conversation topic
   - Context about what was being worked on
   - Note that it was auto-extracted from HTML

Example output format:

```
# Claude Conversation: [Topic]

This is a cleaned version of a conversation about [brief description].

## Context
[Brief context about the issue/task]

---

*Note: Automatically extracted and cleaned from HTML export.*

---

### User:
[User message content]

### Assistant:
[Assistant message content]
```

The goal is to create a version that can be easily fed back into Claude in a new
conversation while preserving all technical details, commands, errors, and
context.

Please note: You do not need to ask permission to write the cleaned version
unless its filename will overwrite an existing file.
