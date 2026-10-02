ALTER TABLE lessons
ADD COLUMN summary TEXT NULL AFTER content;

UPDATE lessons SET
    content = 'Phishing is a form of deception that tries to make a person reveal information or take an unsafe action. It often arrives through messages or websites that appear familiar.\n\nThe person behind the message may pretend to represent a company, school, service, or someone the recipient knows. The false identity is meant to make the request feel normal.\n\nA message can create pressure by suggesting that an account or opportunity is at risk. Pausing helps you notice that pressure instead of reacting immediately.\n\nLearning to recognize warning signs gives you time to verify a request through a trusted, separate channel.',
    summary = 'Phishing uses deception to prompt unsafe actions or disclosure of information.\nPausing and verifying a request through a trusted channel can reduce risk.'
WHERE id = 1;

UPDATE lessons SET
    content = 'Suspicious messages may arrive unexpectedly or ask for an action that does not fit the situation. Notice whether the request is relevant and whether you expected it.\n\nPressure, secrecy, unusual rewards, or threats can be signs that someone wants you to act before thinking. These signals are reasons to check, not proof by themselves.\n\nLook carefully at the sender and the context. A familiar display name does not prove who sent a message.\n\nUnexpected requests for passwords, payment, or personal details deserve extra care. Verify them using a contact method you already trust.',
    summary = 'Unexpected requests, pressure, and unusual demands can signal risk.\nCheck the sender and verify sensitive requests independently.'
WHERE id = 2;

UPDATE lessons SET
    content = 'Many phishing attempts follow a simple pattern: gain attention, create a reason to act, and guide the recipient toward a risky decision. Recognizing the pattern is more useful than memorizing one message.\n\nAn attacker may imitate a familiar organization or refer to information about the recipient. Personal details do not make a request trustworthy.\n\nThe request may seek a password, payment, private information, or access to an account. Consider what would happen if the request were followed.\n\nPause and verify the request through a separate trusted channel. Report messages that seem suspicious using the process available at your organization or school.',
    summary = 'Phishing attempts often combine a believable identity with a reason to act.\nAssess the request and verify it separately before responding.'
WHERE id = 3;

UPDATE lessons SET
    content = 'Email is useful for work and everyday communication, which also makes it a common way to deliver deceptive requests. An unexpected email should be reviewed in context.\n\nCheck whether the sender and the request make sense together. A familiar name or logo is not enough to confirm that a message is genuine.\n\nBe cautious when an email creates urgency, asks for confidential information, or expects an unexpected payment or file action. These details should prompt verification.\n\nContact the person or organization using contact information you already know. If the message is suspicious, follow your organization reporting process instead of replying.',
    summary = 'Email phishing can imitate familiar people or organizations.\nReview the request and verify it through a known contact method.'
WHERE id = 4;

UPDATE lessons SET
    content = 'Smishing is phishing delivered through text messages. A short message can still make a high-impact request or encourage a hurried response.\n\nTreat unexpected notices about accounts, deliveries, payments, or prizes with care. The topic may be familiar even when the request is not.\n\nDo not let a message create pressure to share private information or approve an action immediately. Stop and check whether the notice makes sense.\n\nOpen the official service through an app or address you already know, or contact the organization using a trusted number. Report suspicious messages where that option is available.',
    summary = 'Smishing uses text messages to create pressure or request information.\nCheck notices through an official app or trusted contact method.'
WHERE id = 5;

UPDATE lessons SET
    content = 'Spear phishing is a targeted attempt that is tailored to a person or group. It may refer to a role, project, relationship, or event to appear more credible.\n\nPersonalized details can come from ordinary conversations or information that is broadly available. Their presence does not confirm the sender identity.\n\nPay special attention to requests involving confidential information, money, account access, or changes to established procedures. Familiar context does not remove the need to verify.\n\nConfirm unusual requests with the person involved using a separate trusted channel. Share concerns with a manager or security contact when appropriate.',
    summary = 'Spear phishing uses tailored details to make a request feel familiar.\nVerify sensitive or unusual requests independently, even when context seems accurate.'
WHERE id = 6;

UPDATE lessons SET
    content = 'Consider a banking customer who receives an unexpected notice about account activity. The message creates concern and asks the customer to act before checking what happened.\n\nThe customer notices that the request is unexpected and that the pressure makes it difficult to think clearly. Those are reasons to stop and verify.\n\nInstead of using contact details supplied with the notice, the customer opens the bank service through a familiar route or calls a trusted number. The bank can confirm whether action is needed.\n\nThe lesson is to protect credentials and payment information, avoid hurried decisions, and report suspected fraud to the bank through its established channels.',
    summary = 'An urgent account notice should be checked through a trusted banking channel.\nDo not share credentials or payment details in response to an unexpected request.'
WHERE id = 7;

UPDATE lessons SET
    content = 'A social media user may receive an unexpected notice claiming that an account needs attention. The notice can use concern about losing access to encourage a quick response.\n\nA familiar service name does not prove that a notice came from the service. Look for unexpected requests and consider whether you were already using the official service.\n\nAvoid sharing passwords or verification codes in response to a message. Open the service through its installed app or another trusted route to review account notices.\n\nUse a unique password and available account protections. If access appears compromised, follow the service recovery process and warn contacts if suspicious activity was sent from the account.',
    summary = 'A false account notice may try to rush a user into revealing access details.\nCheck alerts through the official service and never share verification codes.'
WHERE id = 8;

UPDATE lessons SET
    content = 'A strong password is difficult for someone else to guess and is not reused across important accounts. Reuse can allow one exposed password to put several accounts at risk.\n\nLong passphrases made from unrelated words can be easier to remember than short, complicated strings. Choose a phrase that is not based on public personal details.\n\nA reputable password manager can help create and store a different password for each service. Protect the manager account with a strong, unique password and additional security where available.\n\nNever send passwords in messages or share them with people who request them unexpectedly. If a service reports a breach, follow its trusted guidance to update affected access.',
    summary = 'Use long, unique passwords and avoid reusing them across services.\nA password manager can help store credentials more safely.'
WHERE id = 9;

UPDATE lessons SET
    content = 'Multi-factor authentication adds a second check when someone signs in. It can make account access harder for an unauthorized person even if a password is exposed.\n\nThe additional check may use an authenticator app, a security key, or another method offered by the service. Stronger options may be available depending on the account.\n\nNever approve a sign-in prompt or share a one-time code when you did not start the sign-in. Repeated unexpected prompts can mean someone is trying to access the account.\n\nKeep recovery options current and store backup codes securely when a service provides them. Contact the service through its official support process if you suspect account access is at risk.',
    summary = 'Multi-factor authentication adds a separate check to account sign-in.\nReject unexpected prompts and never share one-time verification codes.'
WHERE id = 10;

UPDATE lessons SET
    content = 'Safe browsing begins with a moment of attention before you follow a request. Consider whether the site or action is expected and how you arrived there.\n\nA secure connection does not prove that a site is honest. Check that the service is the one you intended to visit and use a trusted route for sensitive tasks.\n\nKeep your browser, operating system, and security tools updated. Updates can address known weaknesses and improve protection.\n\nAvoid entering sensitive details on shared devices you do not trust. Sign out when finished, and report suspicious pages or messages through an appropriate support channel.',
    summary = 'Safe browsing combines careful decisions with current software and trusted routes.\nA secure connection alone does not prove that a site is legitimate.'
WHERE id = 11;