-- Sprint 13: Simulations 2.0, five ordered decisions per simulation.
-- Back up the database before applying. Existing simulation and attempt IDs are preserved.
-- Existing scenario IDs stay as step 1; attempt history defaults to version 1.
-- Safe to rerun: DDL/index creation is conditional and scenario rows are keyed by simulation_id/step_order.

SET NAMES utf8mb4;

SET @step_order_exists := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'simulation_scenarios'
      AND COLUMN_NAME = 'step_order'
);
SET @step_order_ddl := IF(
    @step_order_exists = 0,
    'ALTER TABLE simulation_scenarios ADD COLUMN step_order TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER simulation_id',
    'SELECT 1'
);
PREPARE step_order_statement FROM @step_order_ddl;
EXECUTE step_order_statement;
DEALLOCATE PREPARE step_order_statement;

SET @attempt_version_exists := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'simulation_attempts'
      AND COLUMN_NAME = 'simulation_version'
);
SET @attempt_version_ddl := IF(
    @attempt_version_exists = 0,
    'ALTER TABLE simulation_attempts ADD COLUMN simulation_version TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER simulation_id',
    'SELECT 1'
);
PREPARE attempt_version_statement FROM @attempt_version_ddl;
EXECUTE attempt_version_statement;
DEALLOCATE PREPARE attempt_version_statement;

SET @step_index_exists := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'simulation_scenarios'
      AND INDEX_NAME = 'uq_simulation_step_order'
);
SET @step_index_ddl := IF(
    @step_index_exists = 0,
    'ALTER TABLE simulation_scenarios ADD UNIQUE KEY uq_simulation_step_order (simulation_id, step_order)',
    'SELECT 1'
);
PREPARE step_index_statement FROM @step_index_ddl;
EXECUTE step_index_statement;
DEALLOCATE PREPARE step_index_statement;

CREATE TEMPORARY TABLE sprint13_scenario_seed (
    simulation_id INT NOT NULL,
    step_order TINYINT UNSIGNED NOT NULL,
    scenario_text LONGTEXT NOT NULL,
    correct_answer ENUM('phishing','legitimate') NOT NULL,
    explanation TEXT NOT NULL,
    PRIMARY KEY (simulation_id, step_order)
) ENGINE=InnoDB;

INSERT INTO sprint13_scenario_seed (simulation_id, step_order, scenario_text, correct_answer, explanation) VALUES
(1,1,'You receive an email claiming that your bank account will be suspended within 30 minutes. It asks you to follow a link and enter your password and one-time code.','phishing','Explanation: The threat, deadline, sign-in link, and request for a one-time code are intended to rush you into surrendering account access.\nLearning outcome: Verify account alerts by opening the bank app or website independently. Never disclose a one-time code.'),
(1,2,'You open your bank app yourself. A notification inside the app reports a card purchase you recognize, and the transaction appears in your account history.','legitimate','Explanation: You reached the bank independently, and the in-app alert matches a transaction you recognize. The classification depends on verified context, not branding alone.\nLearning outcome: Check financial activity through a trusted route instead of following links in unexpected messages.'),
(1,3,'An email says your monthly statement is attached as a password-protected archive. It asks you to open the file and use your online banking password to view it.','phishing','Explanation: The unexpected archive and request to reuse banking credentials are suspicious. A bank statement should be accessible through the bank official service.\nLearning outcome: Do not open unexpected financial attachments or enter account credentials into document prompts.'),
(1,4,'A caller says they are from your bank fraud team and asks you to read a one-time code aloud to stop a transfer. You did not call the bank.','phishing','Explanation: The code may authorize account access or a transaction. An unsolicited caller identity is not established by knowing your name or account details.\nLearning outcome: End the call and contact the bank using a number from your card or statement.'),
(1,5,'Your business is paying a regular supplier. The invoice matches the purchase order, and the payment is entered in the company approved banking workflow with its usual second-person review.','legitimate','Explanation: The transaction follows established procedures and matches an independently recorded purchase. The decision relies on verification, not the invoice appearance.\nLearning outcome: Use documented payment controls and independent review for financial transfers.'),
(2,1,'A text says a package cannot be delivered until you pay a small redelivery fee. It includes a shortened link and says the parcel will be returned in one hour.','phishing','Explanation: The unexpected fee, concealed destination, and time pressure are warning signs. The message does not establish that it came from the carrier.\nLearning outcome: Check delivery status in the carrier official app or through a website reached independently.'),
(2,2,'You are expecting a package. A message in the carrier verified business thread gives a delivery window, requests no payment or personal information, and matches the tracking status in the carrier app.','legitimate','Explanation: The update is consistent with a delivery you expect and independently confirmed. No link or sensitive action is requested.\nLearning outcome: Context and independent confirmation matter; a familiar-looking sender alone is not proof.'),
(2,3,'A contact messages you on WhatsApp saying they lost their phone and urgently need money sent to a new account. They ask you not to call because they are in a meeting.','phishing','Explanation: The urgent payment, changed account details, and request to avoid direct confirmation could indicate impersonation or account takeover.\nLearning outcome: Verify financial requests with the person through a separate, known contact method.'),
(2,4,'A text claims that a suspicious bank transfer is pending. It asks you to reply with the code just sent to your phone to cancel it.','phishing','Explanation: The code may authorize a sign-in or transaction. A text claiming to protect your account can itself be an attempt to take it over.\nLearning outcome: Never share verification codes in response to unsolicited messages; check your account in the official app.'),
(2,5,'You started a support chat through your bank official app. The same app displays a case number and confirms that a representative replied; no password or code is requested.','legitimate','Explanation: You initiated the conversation through a trusted route, and the confirmation remains inside that service.\nLearning outcome: Start support conversations from official apps or known contact details, and keep credentials private.'),
(3,1,'A direct message says your social media account will be deleted unless you appeal immediately. The link opens a sign-in page on a domain that differs from the service official address.','phishing','Explanation: The threat and look-alike domain are designed to collect credentials. A familiar logo does not authenticate the page.\nLearning outcome: Open the service through its official app or a saved address and check account notices there.'),
(3,2,'You open the social platform using your saved bookmark and enter your unique password. The authenticator prompt appears immediately afterward, matching the sign-in you just started.','legitimate','Explanation: The sign-in was initiated through a trusted route, and the second-factor prompt corresponds to your action.\nLearning outcome: Approve authentication prompts only when they match a sign-in you personally initiated.'),
(3,3,'Someone claiming to be platform support contacts you in a direct message. They say they can restore your account if you send them the recovery code you just received.','phishing','Explanation: Recovery codes can grant control of an account. An unsolicited contact asking for one is not verified support.\nLearning outcome: Never share recovery codes; use the platform official recovery process.'),
(3,4,'You receive several sign-in approval prompts while not using the platform. A caller then says they are support and asks you to approve one to stop the alerts.','phishing','Explanation: Repeated prompts may indicate someone is trying to sign in with your password. Approving an unexpected prompt could authorize that access.\nLearning outcome: Reject unsolicited prompts, change exposed credentials through the official service, and report the activity.'),
(3,5,'You request a password reset from the platform official website. A reset email arrives, and you confirm it was expected and that its destination is the service genuine domain before continuing.','legitimate','Explanation: The reset follows an action you initiated, and you verified the destination before entering information.\nLearning outcome: Use unique passwords and confirm that recovery actions were initiated by you.'),
(4,1,'A company director requests a payment through the company approved finance workflow. The invoice matches the purchase order, and the system requires the usual second approver.','legitimate','Explanation: The request is verified through the established workflow and preserves normal approval controls. A sender title alone would not be sufficient evidence.\nLearning outcome: Apply the same payment verification process to executive requests as to any other transfer.'),
(4,2,'A known supplier uploads an invoice to the company vendor portal. The amount, purchase order, and bank details match the records already on file.','legitimate','Explanation: The invoice is received through the established portal and matches independently maintained records.\nLearning outcome: Use approved vendor channels and compare invoices with purchase records before payment.'),
(4,3,'An email in an existing supplier conversation says the supplier bank account has changed. It requests same-day payment and asks you to skip the normal callback because the contact is unavailable.','phishing','Explanation: A compromised email account can appear within a real conversation. A payment change combined with urgency and bypassed verification is high risk.\nLearning outcome: Confirm account changes using a trusted number already on file and follow approval procedures.'),
(4,4,'An email from someone claiming to be IT says your account will be disabled unless you enter your password into a linked security check.','phishing','Explanation: The message uses a threat to prompt credential disclosure. Legitimate support should not need your password through an email link.\nLearning outcome: Report the message and contact IT through the company known help desk channel.'),
(4,5,'An unexpected email contains a spreadsheet labeled Updated supplier list. Opening it displays a warning that you must enable macros to view the information.','phishing','Explanation: An unexpected attachment that requests active content can execute unsafe actions. The label and business context do not prove the file is legitimate.\nLearning outcome: Confirm unexpected files with the sender through a separate channel and follow company attachment-reporting procedures.');

START TRANSACTION;

UPDATE simulation_scenarios AS existing
INNER JOIN sprint13_scenario_seed AS seed
    ON seed.simulation_id = existing.simulation_id
   AND seed.step_order = existing.step_order
SET existing.scenario_text = seed.scenario_text,
    existing.correct_answer = seed.correct_answer,
    existing.explanation = seed.explanation;

INSERT INTO simulation_scenarios (simulation_id, step_order, scenario_text, correct_answer, explanation)
SELECT seed.simulation_id, seed.step_order, seed.scenario_text, seed.correct_answer, seed.explanation
FROM sprint13_scenario_seed AS seed
WHERE NOT EXISTS (
    SELECT 1
    FROM simulation_scenarios AS existing
    WHERE existing.simulation_id = seed.simulation_id
      AND existing.step_order = seed.step_order
);

DROP TEMPORARY TABLE sprint13_scenario_seed;

COMMIT;
