# CANDY AI Execution Discipline

Purpose: prevent GPT-specific waste in CANDY production work.

## 1. Required Documentation Design

The required design is:

- `AGENTS.md` must contain only basic rules.
- `HP/AGENTS.md` must contain only the HP table of contents, routing, and work entry points.
- All other detailed rules must be separated into markdown files according to the user's instruction and the task type.
- Do not force every task through broad document reading.
- Route by task type, then read only the minimum required document.
- Do not make the operator pay for GPT token-saving behavior, broad interpretation, or repeated document scanning.

## 2. Non-Negotiable Rule

Do not turn a short execution task into a long explanation task.

The operator's command is the source of truth. If the operator asks a question, answer the question first. Do not start investigation, editing, generation, commit, push, or deployment until the requested answer is given.

## 3. Execution Priority

For production commands such as area page creation:

1. Identify the task type.
2. Route to the specific procedure.
3. Run the designated command immediately.
4. Report STOP immediately if the command stops.
5. Do not add background explanation before command execution or STOP reporting.

## 4. Forbidden GPT Behavior

- Do not expand the task into unrelated AGENTS, Git, environment, or repository commentary.
- Do not explain for minutes before running the required command.
- Do not claim progress without a completed command result.
- Do not present assumptions as verified facts.
- Do not hide delay behind verbose status messages.
- Do not edit files when the user only asked whether you understood.
- Do not treat conversational agreement as implementation.
- Do not use AGENTS or management docs as an excuse for broad reading.

## 5. Required STOP Report

When a command stops, report only:

```text
Target:
STOP reason:
Completed:
Not executed:
Next command:
```

Long explanations are allowed only after the operator asks for them.
