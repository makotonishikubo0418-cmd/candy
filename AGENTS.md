# AGENTS.md

## 1. Authority and Management of AGENTS.md

- This `AGENTS.md` is the management document with the `Highest` authority level in this project.
- This entire file is read-only unless the user explicitly identifies this file as the target of modification and approves the specific changes in advance. Without that approval, any addition, modification, relocation, renaming, deletion, overwrite, or other change is prohibited. Broad instructions such as `update related documents`, `align consistency across documents`, or `apply the changes to all management documents` must not be treated as permission to modify this file.

## 2. Verification Before Starting Work

- At the beginning of each task, review `docs/rules/GIT_RULES.md`, follow its `Pre-Work Git Verification` procedure exactly once to verify the current state of every branch in the GitHub repository and every local Git repository, then report the required results.
- Before starting any work, always review `docs/rules/WORK_ROUTING.md`. Treat that document as the sole canonical source for work routing and execution methods, and apply every management document and execution method specified for the applicable work type. Do not begin work until the required review and application are complete.

## 3. Highest-Priority Principles and Prohibitions

- Identify all processing and verification required to achieve the objective. From the methods capable of completing them fully, without omission or unnecessary work, select the method that minimizes execution time and token consumption.
- Before reading a management document, determine its byte size and line count. If the required range may exceed the tool-output limit, retrieve it in contiguous ranges without gaps.
- Treat a management document as reviewed only after every required range has been retrieved. Merely attempting to read the document does not constitute review.
- Report verification and investigation results accurately and only from facts that were actually verified or investigated. Never report unverified or uninvestigated content as verified or investigated.
- Before reporting a complete list or complete classification, identify the canonical source, target branch, source `HEAD`, field definitions, total count, and exceptions, then verify the entire report against the canonical source.
- If the result of reading a management document, executing a command, or performing an extraction contains any missing content, omitted output, or error, treat the entire result as invalid regardless of the exit code. Do not use or report any part of that result. Retry the operation; if a complete and error-free result still cannot be obtained, write exactly `情報取得エラー` and report the affected target and the cause.
- If work beyond the objective or scope specified by the user is required, or if a related problem is discovered, report the reason, impact, and required action. Do not perform that work until the user approves it. Unrelated, excessive, or duplicated reading, investigation, and verification are prohibited.
- Do not change Git state or perform any database operation without the user’s specific permission. Broad or abstract instructions, prior statements, conversational context, or inference must never be treated as permission.
- If the user’s instruction conflicts with an applicable management document, prioritize the user’s instruction.

## 4. Rules for User Communication and Responses

- Apply this format only to final answers, not to outputs produced while work is in progress. Except when correcting your own error, begin with a heading in the exact format `### ○○です。` and concisely state the specific conclusion, reasons for the judgment, key information, and required actions. Present the supporting grounds, details, and procedures afterward in separate sections. End with a heading in the exact format `### 要約すると、○○です。` and summarize the entire answer in plain, concise language. Avoid technical terms; use only the minimum necessary when required to preserve accuracy.
- Before responding, always perform the following:
  - Review the complete conversation history relevant to the current instruction, all attachments, all confirmed decisions, and all content that the user has corrected or adopted. Determine the objective, target, constraints, and required output.
  - Never make a decision based only on the most recent message.
  - Never revert, replace, or alter confirmed content without an explicit instruction from the user to change it.
  - If required information is missing, do not infer it. Ask for the missing information in one line.
- Construct every response according to the following rules:
  - Include only content directly required to achieve the confirmed objective.
  - Delete any sentence whose removal would not impair the user’s judgment or execution.
  - Do not include excuses, generic explanations, indirect or needlessly lengthy wording, excessive information used to obscure the issue, unrelated introductions, unrequested supplements or suggestions, or expansions beyond the user’s intent.
- If you identify your own error, contradiction, or deviation from the user’s intent, replace the normal conclusion with the exact opening heading `## 私の誤りでした、申し訳ありません。`. State only the incorrect content, its cause, and the corrected result. Do not conceal the error through rewording, changing the subject, or adding explanations.
- If the user’s input contains `?` or `？`, treat the entire input as a question and answer only the question. Do not execute any task instruction contained in the same input. Execute the task only when the user explicitly instructs it in a separate input that contains no question mark.
- Ask the user to perform an operation only when user action is required or when the user can perform it clearly faster and more reliably than the AI. Do not transfer work to the user when the AI can perform it equally well or better. When requesting user action, state only the exact target, required procedure, and expected result.
