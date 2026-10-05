-- Sprint 12: complete lesson content and lesson quizzes.
-- Back up the database before applying. Existing quiz/question/option rows and attempts are preserved.
-- Re-runnable: summary DDL is conditional, quizzes are checked by lesson, and new content is keyed by text.

SET NAMES utf8mb4;

SET @summary_column_exists := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'lessons'
      AND COLUMN_NAME = 'summary'
);
SET @summary_ddl := IF(
    @summary_column_exists = 0,
    'ALTER TABLE lessons ADD COLUMN summary TEXT NULL AFTER content',
    'SELECT 1'
);
PREPARE summary_statement FROM @summary_ddl;
EXECUTE summary_statement;
DEALLOCATE PREPARE summary_statement;

START TRANSACTION;

UPDATE lessons SET
content = 'Introduction
Phishing is a form of social engineering in which someone pretends to be a trusted person or organization to influence a recipient. The goal is to make a person disclose sensitive information, send money, approve access, or perform another action that benefits the attacker. Phishing can arrive through email, text messages, social networks, phone calls, or fake websites. It is not a software virus and it is not limited to poorly written messages. Many attempts are carefully tailored and can look ordinary. Learning the underlying pattern helps people slow down, assess a request, and verify it before acting.

Main Concepts
Attackers use a believable identity and a reason for contact. The identity may imitate a bank, employer, delivery service, school, colleague, or online platform. The reason may be a payment, account warning, shared document, delivery update, or request from a manager. Familiar logos and names are easy to copy, so appearance alone does not authenticate the sender.

The requested action is important. A phisher may seek login credentials, payment card details, personal identification, one-time verification codes, confidential files, or access to a work system. Sometimes the immediate objective is not data theft: the message may persuade a recipient to change payment instructions, install an unwanted program, or approve a sign-in. Consider what the sender wants you to do and what access that action would provide.

Phishing succeeds by exploiting normal human responses such as trust, curiosity, helpfulness, fear, or a desire to meet a deadline. Pressure is common because a rushed recipient has less time to inspect details. Yet urgency alone is not proof of fraud; it is a signal to pause and check through an independent channel.

The impact can extend beyond one account. A stolen personal password may expose private messages, photos, purchases, or financial services. In an organization, one compromised account can expose shared documents, customer information, or internal systems. An incident can also interrupt operations, create financial losses, and damage trust. The consequences depend on what the account can access and how quickly the event is contained.

Real Example
A student receives an email that appears to come from the campus portal. It says the account will be disabled that afternoon unless the student signs in through a supplied link. The page resembles the real portal and asks for a username, password, and verification code. Instead of following the link, the student opens the portal using a saved bookmark and finds no warning. The student reports the email to campus support. The important clue was not a typo; it was an unexpected high-pressure request for credentials that could be checked independently.

How to Protect Yourself
1. Pause when a message asks for credentials, money, codes, or access.
2. Verify the sender and request using a contact method you already trust.
3. Open services through a saved bookmark or official app, not an unexpected link.
4. Never share a one-time code or approve a sign-in you did not initiate.
5. Report suspected phishing promptly and follow the service recovery guidance if you responded.

Key Takeaways
- Phishing is deception designed to influence an action or obtain information.
- A familiar name, logo, or polished message does not prove authenticity.
- Independent verification interrupts the attack before it reaches its goal.',
summary = '- Phishing uses impersonation and influence to prompt an unsafe action.
- Attackers may seek credentials, money, codes, data, or access.
- Verify unexpected sensitive requests through a trusted channel.'
WHERE id = 1;

UPDATE lessons SET
content = 'Introduction
Phishing messages often contain clues, but no single clue proves a message is fraudulent. A typo can appear in a legitimate note, and a polished message can still be malicious. The useful skill is to evaluate several details together: who sent the message, whether the request fits the context, where links lead, and what action is being requested. The message should be treated as unverified until the important details make sense. A short pause can prevent a rushed click or disclosure.

Main Concepts
Sender identity needs more than a display name. Inspect the full email address or phone number when possible and compare it with a known contact record. A look-alike domain may replace a character, add a word, or use a different ending. An account that appears familiar could also be compromised, so consider whether the request and writing style are consistent with previous communication. For important requests, contact the person using a separate, known method.

Pressure, secrecy, threats, and unusual rewards are common persuasion tactics. Messages may claim that an account will close, a payment is overdue, a package is waiting, or a benefit expires immediately. A legitimate organization may have deadlines, so urgency is not conclusive; however, pressure should make you verify before acting. Requests to bypass normal approval or keep a transaction secret deserve particular scrutiny.

Inspect links without opening them. On a computer, hovering may reveal the destination; on a phone, a long press may preview it, though device behavior varies. Compare the destination domain with the service you intended to reach. Shortened links hide the final address, while a secure connection indicator does not prove that the destination belongs to a trustworthy organization. When in doubt, navigate independently.

Unexpected attachments can contain harmful files or lead to credential-harvesting pages. Be cautious with files you were not expecting, especially when the message asks you to enable active content, macros, or unusual permissions. Confirm the file with the sender through another channel and use your organization''s reporting process. Spelling and grammar may help, but modern phishing can be fluent and carefully formatted.

Real Example
An employee receives a polished message from a display name matching the finance director. It asks for a confidential supplier payment before a stated cutoff and says not to call because the director is in a meeting. The employee checks the full sender address, notices an unfamiliar domain, and contacts the director through the company directory. The director confirms that no payment was requested. The suspicious domain, unusual secrecy, and bypassed procedure formed a stronger case than spelling alone.

How to Protect Yourself
1. Compare sender addresses with trusted records, not just display names.
2. Treat urgency or secrecy as a prompt to verify independently.
3. Preview link destinations and use the official site or app instead.
4. Confirm unexpected attachments before opening or enabling their contents.
5. Report suspicious messages even when you are unsure whether they are fraudulent.

Key Takeaways
- Evaluate sender, context, destination, and requested action together.
- Urgency, attachments, and shortened links call for verification.
- Polished language does not establish that a message is authentic.',
summary = '- Compare the sender and request with trusted context.
- Treat pressure, unfamiliar links, and unexpected attachments cautiously.
- Verify sensitive requests independently; no single clue is conclusive.'
WHERE id = 2;

UPDATE lessons SET
content = 'Introduction
Phishing operations vary in effort, but many follow a recognizable sequence: identify a target, prepare a believable approach, influence the recipient, and use the resulting access or information. Understanding this sequence helps people recognize risk before a message arrives and respond effectively afterward. It does not require assuming every unusual message is an attack. Instead, learners can identify what the sender is trying to accomplish, question whether the request is expected, and choose a verification method that does not depend on the suspicious message.

Main Concepts
Reconnaissance is the collection of information that may help an attacker choose a target or make a message convincing. Information can come from public websites, social media, business directories, prior data exposures, or ordinary correspondence. A public job title or project announcement may provide context for a tailored message. Personal details do not authenticate the sender; they can be used as decoration to create familiarity.

During preparation, the attacker selects a channel and constructs a pretext. The pretext might be a shared document, invoice, password reset, delivery issue, or urgent request from a supervisor. The sender may imitate a known domain, create a look-alike website, or compromise a legitimate account. Some attempts target many people with a broad message; others target one person or a small group with personal details. The amount of preparation differs, but both approaches can be dangerous.

Social engineering is the use of influence to encourage a person to disclose information or take an action. Common tactics include authority, urgency, fear, curiosity, helpfulness, and secrecy. A message can ask directly for a password or lead to a fake sign-in page. Other messages request a payment, an approval, a file download, or a verification code. Credential theft may let an attacker access accounts, impersonate the victim, or search for additional information. Reusing a password can increase the damage if the same credential works elsewhere.

Exploitation is what happens after a recipient responds. An attacker may use stolen credentials, alter account settings, send messages from a compromised account, or attempt access to connected resources. The goal is not always obvious at first. Quick reporting and use of official account recovery steps can help limit further activity. Organizations should use established reporting and incident procedures rather than asking employees to investigate suspicious links themselves.

Real Example
A staff member posts publicly about preparing a conference presentation. A few days later, an email from a similar-looking address claims to be a colleague and shares a document needed for the event. The page asks the staff member to sign in again. The timing and project details seem convincing, but the staff member opens the company collaboration tool from a saved bookmark and finds no shared document. They report the message to IT. Public context made the pretext believable, but independent verification exposed the mismatch.

How to Protect Yourself
1. Limit public personal and work details that could support impersonation.
2. Verify unexpected requests through a separate known contact method.
3. Use unique passwords and never disclose passwords or sign-in codes.
4. Follow normal approval procedures even when a request seems urgent.
5. Report suspected compromise quickly and use official recovery channels.

Key Takeaways
- Attacks may involve reconnaissance, preparation, influence, and exploitation.
- Personalization can be researched and does not prove identity.
- A suspicious action should be verified outside the message channel.',
summary = '- Phishing can progress from reconnaissance to exploitation.
- Attackers use personal context and social pressure to influence decisions.
- Verification, unique passwords, and prompt reporting reduce impact.'
WHERE id = 3;

UPDATE lessons SET
content = 'Introduction
Email phishing uses deceptive email to make a recipient reveal information, visit a counterfeit site, open an unsafe file, or approve a request. Email remains useful for ordinary work, which gives deceptive messages a familiar setting. A message can imitate a bank, school, vendor, colleague, or internal department. Some are mass-produced; others are crafted for a particular person. Learning to examine the sender, domain, context, and requested action is more reliable than relying on the logo or the message appearance.

Main Concepts
Email spoofing makes the sender information appear to belong to someone else. The visible display name can be changed easily, and look-alike addresses may use small spelling differences or extra words. Technical email authentication can reduce some forms of spoofing, but users should still confirm unusual requests through known contact details. A message arriving in an existing conversation is not automatically safe if the account may have been compromised.

Fake domains are designed to resemble a real service. Attackers may add a descriptive word, change a character, or use a different domain ending. When a message contains a link, compare its destination to the official domain and consider whether you expected the request. A page using HTTPS may protect the connection to that page, but it does not prove the page is operated by the legitimate company. For account activity, open the official app or type a known address yourself.

Attachments may be harmful, may exploit outdated software, or may direct a user to a fraudulent login page. Be especially careful when a message unexpectedly asks you to enable macros, run a script, install a viewer, or ignore a security warning. Confirm the sender and file through a separate channel. Follow workplace procedures for reporting and analysis rather than forwarding suspicious files to colleagues.

A plausible email can still request something abnormal, such as an urgent payment to a new bank account or a password reset outside normal process. Compare the request with established procedures. If you already entered credentials, use the official service to change the password, revoke active sessions where possible, and alert the appropriate support team.

Real Example
A purchasing employee receives an invoice from a familiar supplier name. The sender says the supplier changed banks and asks for payment that day. The employee checks the full address and sees a domain that differs from the supplier''s known domain. Rather than replying, the employee calls the number in the vendor record. The supplier confirms its banking details have not changed. The organization then reports the email and follows its payment verification policy. The key signal was the sensitive change request and the unfamiliar sender domain.

How to Protect Yourself
1. Inspect the full sender address and compare it with trusted contact records.
2. Verify payment, password, and account changes using established procedures.
3. Navigate to services independently instead of trusting an email link.
4. Confirm unexpected attachments and never enable unrequested active content.
5. Report suspicious email through your organization''s approved channel.

Key Takeaways
- Display names and logos are easy to imitate.
- Fake domains and attachments can lead to credential theft or unsafe actions.
- Verify high-impact requests through a separate trusted channel.',
summary = '- Email phishing can spoof identities and imitate familiar organizations.
- Inspect domains and treat unexpected attachments with care.
- Verify payment or credential requests through known channels.'
WHERE id = 4;

UPDATE lessons SET
content = 'Introduction
Smishing is phishing delivered through SMS or another text-messaging service. Short messages can create the same risks as email: a recipient may visit a fake site, disclose a code, make a payment, or install an unsafe application. A text may claim to concern a delivery, bank account, toll, prize, or service problem. The small screen and limited sender details can make close inspection harder. Treat an unexpected text as an unverified request, even when it mentions a service you use.

Main Concepts
Delivery scams often claim that a package cannot be delivered until the recipient pays a small fee or confirms an address. Banking scams may warn of a locked account or suspicious transaction and encourage immediate action. Other texts imitate public agencies, subscription services, employers, or people in the recipient''s contact list. The topic may fit everyday life, but that does not establish the message''s source.

A shortened link hides the destination address and can redirect through several sites. Some phones show previews, but a preview is not a guarantee of safety. Avoid opening unexpected links. Instead, check delivery status in the carrier''s official app or on a website reached independently. For banking concerns, use the official banking app or a phone number printed on a payment card or statement. Do not use contact details in the suspicious text.

Attackers may use spoofed sender names or numbers. A message appearing in a familiar thread is not conclusive evidence that it was sent by the real organization; messaging systems and accounts can be abused. Never send a password, full payment details, or a one-time verification code in response to an unsolicited text. A real support representative should not need you to disclose a one-time code that approves access to your account.

If a text asks you to install an application, move a conversation to another service, or approve a payment, pause and check with the organization through a separate route. Report unwanted or suspicious messages using the messaging service or local reporting process. If you clicked and entered information, contact the affected provider and follow its official account-security steps promptly.

Real Example
A person receives a text claiming that a parcel is waiting and a small redelivery fee is due within two hours. The link is shortened and the sender is not a carrier number they recognize. The person opens the carrier app already installed on their phone and sees no fee or delivery problem. They report the text and delete it. Checking the delivery through an independent route prevented the message from collecting payment details.

How to Protect Yourself
1. Do not follow unexpected shortened links in text messages.
2. Check deliveries and account alerts in the official app or website.
3. Never share passwords or one-time sign-in codes by text.
4. Confirm urgent banking or payment requests with a trusted number.
5. Report suspicious messages and contact the provider promptly if you responded.

Key Takeaways
- Smishing uses text messages to prompt unsafe actions or disclosures.
- Delivery and banking stories can be imitated and should be verified.
- Shortened links conceal destinations; use a trusted route instead.',
summary = '- Smishing uses SMS to imitate delivery, banking, or account notices.
- Avoid unexpected links and verify alerts in official apps.
- Never disclose passwords or verification codes by text.'
WHERE id = 5;

UPDATE lessons SET
content = 'Introduction
Spear phishing is a targeted phishing attempt tailored to a particular person, team, or organization. A message may mention a job role, project, colleague, supplier, event, or internal process to appear credible. Personalization can make a request feel familiar, but it does not verify the sender. The right response is not suspicion of every colleague; it is careful verification when a message asks for sensitive information, money, account access, or an exception to normal procedures.

Main Concepts
Attackers may profile a target using public professional pages, social media, company websites, conference materials, or information from prior incidents. A job title or project name can help an attacker choose an appropriate pretext. Details can also be guessed or copied from a compromised mailbox. Therefore, accurate context should not be treated as proof of identity.

The message may imitate an executive, coworker, customer, or trusted vendor. It can request a file, password reset, payroll change, confidential data, or payment to a new account. Corporate attacks may focus on employees who can authorize transfers or access valuable records. Some messages attempt to move a conversation to a personal account, bypass a second approver, or create secrecy. These actions matter because they change the risk of the request, even when the language appears normal.

Use established verification procedures for sensitive requests. For a payment change, use the vendor contact information already on file and require the organization''s normal approval. For an unusual request from a manager, confirm it through a separate company channel or a direct conversation. Do not reply to the same thread as the only verification method if the account could be compromised. Security teams can help assess suspected messages; employees should use their reporting process rather than investigate a dangerous link themselves.

Spear phishing can be part of a larger attempt to steal credentials or gain access to internal systems. Unique passwords, multi-factor authentication, least-privilege access, and fast reporting can reduce the consequences. These controls do not replace human verification, but they can limit what an attacker can do after a mistake.

Real Example
A staff member receives a message that appears to come from a project lead. It references a real project milestone and asks for a confidential spreadsheet to be sent to a new external address before a meeting. The staff member checks the request in the team''s collaboration channel and contacts the project lead using the company directory. The lead denies sending it. The employee reports the message, allowing the organization to check whether others received it. Correct project details had made the request plausible, not legitimate.

How to Protect Yourself
1. Treat personal or project details as context, not proof of identity.
2. Verify sensitive requests through a separate known company channel.
3. Follow payment, data-sharing, and approval procedures without exceptions.
4. Use unique passwords and available multi-factor authentication.
5. Report targeted messages promptly to the security contact or support team.

Key Takeaways
- Spear phishing uses personalization to increase credibility.
- Sensitive corporate requests need independent verification and normal approvals.
- Accurate details do not prove that the sender controls the identity.',
summary = '- Spear phishing tailors messages using details about a target.
- Personalization does not authenticate the sender.
- Verify sensitive workplace requests through established procedures.'
WHERE id = 6;

UPDATE lessons SET
content = 'Introduction
Banking fraud through phishing often begins with a message that appears to describe an account problem. The sender may impersonate a bank and use fear or urgency to encourage a customer to follow a link, call a supplied number, or reveal sign-in details. This lesson analyzes a representative case rather than one specific bank or incident. The aim is to notice the requested action, verify the message independently, and limit harm if credentials or payment information have already been exposed.

Main Concepts
A fake bank email may copy colors, logos, and standard language. It might warn of a blocked card, unusual transaction, security review, or expiring account access. The message may include a link to a counterfeit page that records a username and password. A page can look authentic and may even use HTTPS; neither appearance nor connection encryption proves that it belongs to the bank.

Credential theft can be followed by attempts to access the real account, change contact details, create transactions, or persuade the customer to disclose a one-time code. Attackers may impersonate the bank in a second phone call or message. A verification code is often part of account access and should not be shared with an unsolicited caller or text sender. A bank employee using an official process should not ask a customer to hand over a code that authorizes a sign-in.

The consequences may include unauthorized transactions, loss of access, identity misuse, time spent recovering accounts, and stress. Prompt action can help: use the bank''s official app or the number printed on a card or statement, report the suspected message, and ask the bank what protective steps to take. If credentials were entered, change them through the legitimate service and avoid reusing the old password elsewhere. Preserve relevant details for the bank''s investigation without forwarding dangerous links to others.

Prevention depends on a repeatable habit, not on recognizing every fake design. Start from a trusted route when checking an alert. Review account activity inside the official service. Use unique passwords and available multi-factor authentication. Be cautious about unexpected requests for payment, login, or codes, and report suspicious communication using a verified contact method.

Real Example
A customer receives an email stating that a card payment has been blocked and that the account will be restricted unless it is confirmed immediately. The email links to a page with the bank''s colors and a sign-in form. The customer avoids the link and opens the bank''s app directly. The app shows no alert, so the customer calls the number on the card and reports the email. The bank confirms it is fraudulent and advises the customer to delete it. Independent verification prevented credential collection.

How to Protect Yourself
1. Check account warnings only through the official bank app or known website.
2. Call a trusted number from your card or statement, not the message.
3. Never share passwords or verification codes with unsolicited contacts.
4. Use a unique banking password and enable available account protections.
5. Contact the bank immediately through official channels if you responded.

Key Takeaways
- A logo and HTTPS connection do not prove that a bank page is genuine.
- Fake alerts aim to collect credentials, payment data, or verification codes.
- Use official bank channels to verify, report, and recover.',
summary = '- Fake banking alerts use urgency to collect credentials or payment details.
- Verify activity in the official app or through a known phone number.
- Contact the bank promptly if you disclose information.'
WHERE id = 7;

UPDATE lessons SET
content = 'Introduction
A fake social media login attempts to collect the credentials a person uses to access a social platform. The attacker may send a notice about a restricted account, copyright complaint, unusual login, or expiring verification. A link leads to a page that imitates the real service. If the user enters a password, the attacker may use it to take over the account, impersonate the user, or target their contacts. Recognizing the pattern and using the official app or website protects more than one account.

Main Concepts
Credential-harvesting pages copy familiar logos, colors, and sign-in fields. The address may use a look-alike spelling, extra words, or an unrelated domain. A page may show HTTPS without being operated by the real social platform. Examine how you reached the page and avoid signing in through an unexpected message. When a notice seems important, open the installed app or type a known address independently and check for alerts there.

Password reuse increases the impact of a stolen credential. If the same password is used on email, shopping, and social accounts, an attacker may try it on other services. The email account is especially important because it can often reset other passwords. Unique passwords and a reputable password manager reduce this risk. Multi-factor authentication adds another obstacle, but users must reject prompts and codes they did not request.

After an account takeover, an attacker may change recovery details, send fraudulent messages to contacts, publish content, or use private conversations to create new scams. Friends may trust messages from the account, allowing the attack to spread. If access is lost, use the platform''s official account-recovery process from its genuine site. Secure the associated email account and other services that reused the password. Warn contacts through another channel if suspicious messages were sent.

Do not respond to threats by sending more personal information or paying an unsolicited recovery service. Scammers sometimes target people who have already lost access. Use platform support pages reached independently, document suspicious activity, and follow the provider''s recovery steps. Report impersonation and fraudulent login notices to the service.

Real Example
A user receives a direct message claiming that a photo violates a platform rule and the account will be deleted within an hour. The included appeal link opens a familiar-looking login page, but the address contains an extra word. The user closes it and opens the official app, where no such warning exists. The user reports the message and checks recent account activity. Because the password is unique and multi-factor authentication is enabled, the attempted credential theft does not become an account takeover.

How to Protect Yourself
1. Open social platforms through the official app or a saved trusted address.
2. Use a unique password for each important account.
3. Enable multi-factor authentication and reject unexpected sign-in prompts.
4. Use official account recovery if access or account details change unexpectedly.
5. Warn contacts through another channel if your account may have sent suspicious messages.

Key Takeaways
- Fake login pages imitate services to harvest credentials.
- Password reuse can turn one stolen password into several compromised accounts.
- Verify alerts independently and use official recovery channels.',
summary = '- Fake login pages imitate social platforms to steal credentials.
- Unique passwords reduce the impact of password reuse attacks.
- Check alerts and recover accounts through the official service.'
WHERE id = 8;

UPDATE lessons SET
content = 'Introduction
A password is one way a service checks that a person is authorized to sign in. A strong password is difficult to guess and is unique to that service. Length and uniqueness matter because passwords can be exposed through phishing, data breaches, malware, or guessing. No password can guarantee an account will never be compromised, but safer creation and storage habits reduce common risks. This lesson focuses on practical choices that people can sustain across many accounts.

Main Concepts
Short passwords based on common words, names, dates, or keyboard patterns are easier to guess. A longer passphrase made from several unrelated words can be easier to remember while providing more possibilities. Avoid details that other people can find in public profiles. Follow the service''s requirements, and do not make predictable substitutions such as changing only one character in an old password.

Never reuse a password across services. If one company experiences a breach, an attacker may test the exposed credential on email, banking, shopping, and work accounts. Unique passwords prevent one service''s incident from automatically exposing other accounts. Prioritize email, financial, work, and password-manager accounts because they may provide access to recovery or valuable information.

A reputable password manager can generate and store long, unique passwords. Protect its main account with a strong unique password and multi-factor authentication where available. Use the manager''s security guidance, keep recovery options current, and do not store the master password where others can casually access it. If a password manager is not available, consider a deliberate method that still produces unique credentials; do not rely on minor variations that are easy to predict.

Do not share passwords in email, chat, or support requests. Legitimate support should not require you to reveal your password. If a service reports a breach, visit it through a trusted route, change the affected password, and change any other account where it was reused. Review active sessions and recovery information. Multi-factor authentication can reduce risk if a password is stolen, but it does not make reuse a good practice.

Real Example
A person uses the same short password for a shopping account and personal email. The shopping service reports a breach, and automated sign-in attempts begin on the email service. Because both passwords match, an attacker may access messages and use password-reset links elsewhere. In a safer setup, the person uses a password manager to create different long passwords and enables multi-factor authentication on email. The shopping breach then does not directly expose the email password.

How to Protect Yourself
1. Create a long password or passphrase that is not based on public details.
2. Use a different password for every important service.
3. Use a reputable manager to generate and store unique credentials.
4. Protect email and manager accounts with multi-factor authentication.
5. Change exposed passwords through the official service and review account sessions.

Key Takeaways
- Long, unique passwords reduce guessing and reuse risks.
- Password managers make unique credentials easier to maintain.
- A breached password should be changed through a trusted service route.',
summary = '- Use long, unique passwords rather than predictable or reused ones.
- A password manager can generate and store credentials.
- Protect email and password-manager accounts especially carefully.'
WHERE id = 9;

UPDATE lessons SET
content = 'Introduction
Multi-factor authentication (MFA) asks a person to provide more than one kind of evidence when signing in. It can protect an account when a password is stolen, guessed, or reused. MFA is not a guarantee: attackers may still trick people into approving a prompt or revealing a code. Understanding the factors and responding carefully to sign-in requests makes MFA more useful. The best method depends on what a service supports and what the user can reliably maintain.

Main Concepts
Authentication factors generally fall into categories: something you know, such as a password; something you have, such as a security key or authenticator device; and something you are, such as a fingerprint. MFA combines evidence from distinct categories. Two passwords are not meaningfully different factors because both are things a person knows. A one-time code generated by an authenticator app can add a possession-based check, while a security key can provide strong phishing resistance when correctly configured.

Authenticator apps create time-based codes or approve sign-in requests. Some services send codes by text message. Any code should be treated as sensitive: a person who asks for it may be trying to complete a login or reset. Never share a code with someone who contacts you unexpectedly. Push-based approval prompts can be abused through repeated requests; reject prompts you did not initiate and report repeated unexpected attempts.

MFA can reduce the chance that a stolen password alone is enough to access an account. It is particularly important for email, financial, work, and administrator accounts. It does not prevent every attack, including a user approving a fraudulent prompt, a compromised device, or some sophisticated real-time phishing attempts. Continue to verify the website and request, and maintain unique passwords.

Set up MFA only through the official service settings. Keep recovery methods current and store backup codes somewhere secure and separate from the device they recover. If a phone or security key is lost, use the service''s official recovery process. Do not disable MFA because of an unsolicited support request. Organizations should provide a clear procedure for replacing a factor without asking employees to send codes in chat.

Real Example
An employee receives several sign-in approval prompts while not trying to log in. A caller then claims to be IT and asks the employee to approve one prompt to stop the alerts. The employee rejects the prompts, does not disclose any code, and contacts IT using the company help desk number. IT confirms that no support request was opened and helps secure the account. The unexpected prompts were evidence to report, not requests to approve.

How to Protect Yourself
1. Enable MFA in the official security settings of important accounts.
2. Prefer a security key or authenticator method when the service supports it.
3. Reject prompts you did not initiate and never share one-time codes.
4. Store backup codes securely and keep account recovery details current.
5. Report repeated prompts or suspected compromise through official support.

Key Takeaways
- MFA combines different categories of proof at sign-in.
- MFA helps when a password is exposed but does not replace verification.
- Unexpected prompts and codes must be rejected and reported.',
summary = '- MFA combines distinct authentication factors.
- Authenticator apps or security keys can add protection beyond passwords.
- Reject unexpected prompts and keep recovery methods secure.'
WHERE id = 10;

UPDATE lessons SET
content = 'Introduction
Safe browsing is a collection of habits for deciding where to navigate, what to download, and when to share information. It does not mean that a browser can label every page as safe. Fraudulent sites can look professional, and legitimate sites can be compromised. A secure connection protects data in transit but does not confirm who operates the site. By verifying URLs, limiting risky downloads, applying updates, and treating public networks thoughtfully, people can reduce avoidable exposure.

Main Concepts
HTTPS indicates that a connection to a website is encrypted and that the browser has checked a certificate for the domain. It does not certify that the site is honest or that a business is legitimate. A phishing site can also use HTTPS. For sensitive actions, check the domain carefully and reach the service through a saved bookmark, official app, or address you enter yourself. Watch for misspellings, extra words, unexpected subdomains, and redirects. The meaningful domain may be less obvious than the text immediately before it.

Download software from the developer''s official site or a trusted app store. Be cautious when a page unexpectedly offers a security update, codec, document viewer, or browser extension. Do not disable protections or grant broad permissions just to view a file. Check that a download is expected and appropriate for the task. On shared or work devices, follow the organization''s software policy and ask support rather than installing unapproved tools.

Software updates can repair known security weaknesses. Keep the operating system, browser, applications, and security tools current using their built-in update mechanisms. Be wary of pop-ups that imitate update warnings and direct you to a download. Updates should come from the software''s normal settings or official source. Remove applications and extensions you no longer need, because unnecessary software can expand the number of components that require maintenance.

Public Wi-Fi can expose users to fake network names or insecure local environments. Encryption on modern websites helps protect traffic, but it does not make every activity safe. Avoid sensitive work on devices you do not control, verify the network name with the venue, and use an organization''s approved remote-access method when required. Always sign out of shared devices and never save credentials on public computers.

Real Example
At an airport, a traveler sees a pop-up claiming that a video cannot play until a browser update is installed. The download comes from an unfamiliar domain. The traveler closes the pop-up, checks the browser''s own update settings, and confirms that it is current. Later, the traveler uses the airline''s official app rather than a link in a public Wi-Fi login page to review a booking. Both choices avoid trusting unexpected prompts.

How to Protect Yourself
1. Verify the domain and use trusted routes for account or payment tasks.
2. Remember that HTTPS encrypts a connection but does not prove a site is legitimate.
3. Download software and updates only through official sources or approved stores.
4. Keep devices and applications updated using their built-in update controls.
5. Avoid sensitive work on untrusted devices and sign out of shared ones.

Key Takeaways
- Verify the actual URL before entering sensitive information.
- HTTPS is useful but cannot establish that a site is trustworthy.
- Trusted downloads, updates, and device habits reduce exposure.',
summary = '- Verify domains and use trusted routes for sensitive browsing.
- HTTPS does not prove that a website is legitimate.
- Use official downloads, timely updates, and care on public networks.'
WHERE id = 11;

CREATE TEMPORARY TABLE sprint12_new_quizzes (
    lesson_id INT NOT NULL PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO sprint12_new_quizzes (lesson_id, title, description) VALUES
(2, 'Common Phishing Indicators Quiz', 'Assess sender, context, urgency, attachments, and link warning signs.'),
(3, 'How Attackers Operate Quiz', 'Assess the stages and social engineering methods used in phishing.'),
(5, 'Smishing Quiz', 'Assess SMS phishing risks and safe verification practices.'),
(6, 'Spear Phishing Quiz', 'Assess targeted attacks, profiling, and workplace defenses.'),
(8, 'Fake Social Media Login Quiz', 'Assess credential harvesting, account takeover, and prevention.'),
(10, 'Multi-Factor Authentication Quiz', 'Assess MFA factors, methods, benefits, and safe use.'),
(11, 'Safe Browsing Habits Quiz', 'Assess URL verification, HTTPS, downloads, updates, and public Wi-Fi.');

INSERT INTO quizzes (lesson_id, title, description, passing_score)
SELECT seed.lesson_id, seed.title, seed.description, 70
FROM sprint12_new_quizzes AS seed
WHERE NOT EXISTS (
    SELECT 1 FROM quizzes AS existing WHERE existing.lesson_id = seed.lesson_id
);

CREATE TEMPORARY TABLE sprint12_new_questions (
    lesson_id INT NOT NULL,
    question_key VARCHAR(8) NOT NULL,
    question_text TEXT NOT NULL,
    explanation TEXT NOT NULL,
    PRIMARY KEY (lesson_id, question_key)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TEMPORARY TABLE sprint12_new_options (
    lesson_id INT NOT NULL,
    question_key VARCHAR(8) NOT NULL,
    option_order TINYINT NOT NULL,
    option_text TEXT NOT NULL,
    is_correct TINYINT(1) NOT NULL,
    PRIMARY KEY (lesson_id, question_key, option_order)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO sprint12_new_questions (lesson_id, question_key, question_text, explanation) VALUES
(1,'Q3','A message claims to be from a bank and asks for a one-time sign-in code. What is the safest interpretation?','A one-time code can authorize account access; verify the request through the bank using a trusted channel.'),
(1,'Q4','Why can a convincing logo not establish that a message is genuine?','Logos and visual styles can be copied and do not authenticate the sender.'),
(1,'Q5','Which response best limits risk when an unexpected message requests sensitive information?','Pause and verify independently before sharing information or taking action.'),
(2,'Q1','An email display name matches your manager, but the request is an unusual payment. What should you do?','Confirm through a known, separate contact method and follow payment approval procedures.'),
(2,'Q2','What does a shortened link make harder to assess?','It conceals the final destination, so the recipient should avoid trusting it without verification.'),
(2,'Q3','Which combination provides the strongest reason to pause and verify?','Unexpected urgency plus a request to bypass normal approvals is a strong warning combination.'),
(2,'Q4','A message contains no spelling errors. What can you conclude?','Correct spelling does not establish authenticity; assess sender, context, destination, and request.'),
(2,'Q5','You receive an unexpected attachment from a familiar contact. What is safest?','Confirm with the contact through another channel before opening or enabling its contents.'),
(3,'Q1','What is reconnaissance in a phishing operation?','It is gathering information that may help select a target or make a pretext convincing.'),
(3,'Q2','Why might an attacker mention a real project in a message?','Public or obtained information can personalize a pretext without proving the sender is genuine.'),
(3,'Q3','Which action is an example of social engineering?','Using urgency or authority to influence someone into disclosing information or taking action.'),
(3,'Q4','What may happen after an attacker steals a work password?','The attacker may attempt account access or use the account to reach connected resources.'),
(3,'Q5','A suspicious request matches your current project. What is the best check?','Verify the request using a separate trusted channel rather than relying on its project details.'),
(4,'Q2','A supplier emails new bank details and requests same-day payment. What should finance do?','Verify the change using contact information already on file and follow approval procedures.'),
(4,'Q3','What does HTTPS on a linked sign-in page prove?','It indicates an encrypted connection, not that the page belongs to the claimed bank.'),
(4,'Q4','Why is an unexpected request for a verification code risky?','The code may authorize a sign-in or transaction and should not be disclosed to unsolicited contacts.'),
(4,'Q5','Where should you check an unexpected bank account warning?','Open the official bank app or contact the bank using a known number.'),
(5,'Q1','A text says a parcel requires a small fee through a shortened link. What is safest?','Check tracking through the carrier app or an independently reached official site.'),
(5,'Q2','What is smishing?','Phishing delivered through SMS or another text-messaging service.'),
(5,'Q3','Why should a text sender name not be treated as proof?','Sender names or numbers can be spoofed, and familiar threads can be abused.'),
(5,'Q4','A text asks for your bank sign-in code. What should you do?','Do not share the code; verify any account issue through the official bank channel.'),
(5,'Q5','A delivery message creates a two-hour deadline. What does that urgency mean?','It is a reason to pause and verify, not proof by itself that the message is genuine.'),
(6,'Q1','What makes a phishing attempt spear phishing?','It is tailored to a particular person, team, or organization.'),
(6,'Q2','A message names your real project. What does that establish?','Only that the sender has or guessed context; it does not authenticate their identity.'),
(6,'Q3','A colleague asks to send confidential data to a new address. What is safest?','Verify through a separate company channel and follow normal data-sharing approvals.'),
(6,'Q4','Why can public professional details increase phishing risk?','They can help an attacker profile a target and create a believable pretext.'),
(6,'Q5','Which control helps limit damage if a workplace password is exposed?','Unique credentials and MFA reduce the chance that one exposed password grants broader access.'),
(7,'Q2','A bank email links to a page with the correct logo and HTTPS. What should you conclude?','Neither the logo nor HTTPS proves the page is operated by the bank.'),
(7,'Q3','What should a customer do after entering bank credentials on a suspicious page?','Contact the bank through an official channel promptly and follow its account-security steps.'),
(7,'Q4','Why should a customer not share a one-time code with an unsolicited caller?','The code may authorize access, and the caller is not verified by the unexpected contact.'),
(7,'Q5','Which route is best for checking an unexpected account alert?','Open the official banking app or call a number from a card or statement.'),
(8,'Q1','What is the purpose of a fake social-media login page?','To collect credentials that may be used to access or take over an account.'),
(8,'Q2','How can password reuse turn one incident into several?','An exposed password may be tried on other services that use the same credential.'),
(8,'Q3','A direct message threatens account deletion and supplies an appeal link. What is safest?','Open the official app independently and check for an alert there.'),
(8,'Q4','What should you do if your account may have sent suspicious messages?','Use official recovery and warn contacts through another channel.'),
(8,'Q5','Why is the associated email account important during recovery?','It may receive password-reset links for the social account and other services.'),
(9,'Q2','Why should important accounts have different passwords?','A breach at one service should not automatically expose other accounts.'),
(9,'Q3','What is a useful role for a reputable password manager?','It can generate and store long, unique passwords for different services.'),
(9,'Q4','Which account deserves especially strong protection because it can reset others?','The primary email account is important because it often supports account recovery.'),
(9,'Q5','A service reports that your password was exposed. What should you do?','Reach the service independently, change the password, and update other accounts where it was reused.'),
(10,'Q1','Which statement describes multi-factor authentication?','It combines evidence from distinct authentication categories.'),
(10,'Q2','Why are two passwords not two different authentication factors?','Both are something the user knows, so they belong to the same category.'),
(10,'Q3','You receive an approval prompt you did not initiate. What should you do?','Reject it and report repeated or suspicious prompts through official support.'),
(10,'Q4','Where should backup codes be kept?','Store them securely and separately from the device they are intended to recover.'),
(10,'Q5','What is a security key an example of?','It is a possession-based authentication method and can provide phishing-resistant sign-in.'),
(11,'Q1','What does HTTPS tell you about a website?','It protects the connection but does not prove the site itself is legitimate.'),
(11,'Q2','Where should browser updates be installed from?','Use the browser built-in update mechanism or the official software source.'),
(11,'Q3','A public Wi-Fi page offers an unexpected browser update. What is safest?','Close it and check for updates using the browser own settings.'),
(11,'Q4','What should you verify before entering payment details?','Check the actual domain and reach the service through a trusted route.'),
(11,'Q5','Why avoid saving credentials on a shared public computer?','Another user may access the saved credentials or an active signed-in session.');

INSERT INTO quiz_questions (quiz_id, question_text, explanation)
SELECT q.id, seed.question_text, seed.explanation
FROM sprint12_new_questions AS seed
JOIN quizzes AS q ON q.lesson_id = seed.lesson_id
WHERE NOT EXISTS (
    SELECT 1 FROM quiz_questions AS existing
    WHERE existing.quiz_id = q.id AND existing.question_text = seed.question_text
);

INSERT INTO sprint12_new_options (lesson_id, question_key, option_order, option_text, is_correct) VALUES
(1,'Q3',1,'Read the code to the sender so the bank can verify the account.',0),(1,'Q3',2,'Treat the code as sensitive and contact the bank through a known channel.',1),(1,'Q3',3,'Reply asking the sender to confirm the account number.',0),(1,'Q3',4,'Post the code in the bank support forum.',0),
(1,'Q4',1,'A logo is a security credential that only the real company can use.',0),(1,'Q4',2,'A copied logo can look convincing without proving who sent the message.',1),(1,'Q4',3,'A logo proves the sender owns the displayed email address.',0),(1,'Q4',4,'A logo means the message has been checked by your bank.',0),
(1,'Q5',1,'Act immediately because a legitimate message would not wait.',0),(1,'Q5',2,'Forward the request to coworkers and ask them to decide.',0),(1,'Q5',3,'Pause and verify the request using contact details you already trust.',1),(1,'Q5',4,'Reply with partial information to test the sender.',0),
(2,'Q1',1,'Pay first, then ask your manager whether the request was expected.',0),(2,'Q1',2,'Confirm with the manager through a known separate channel and follow policy.',1),(2,'Q1',3,'Trust the display name because it matches your manager.',0),(2,'Q1',4,'Forward the payment request to the supplier address in the message.',0),
(2,'Q2',1,'The sender display name.',0),(2,'Q2',2,'The final destination address.',1),(2,'Q2',3,'The message font.',0),(2,'Q2',4,'The time the message arrived.',0),
(2,'Q3',1,'A routine notice with no requested action.',0),(2,'Q3',2,'A familiar logo displayed in the message.',0),(2,'Q3',3,'An urgent request to bypass normal approval steps.',1),(2,'Q3',4,'A message that includes the recipient name.',0),
(2,'Q4',1,'The message must be genuine because it is grammatically correct.',0),(2,'Q4',2,'Spelling is only one clue; other details still need verification.',1),(2,'Q4',3,'The sender identity has been confirmed automatically.',0),(2,'Q4',4,'Links in the message can be trusted.',0),
(2,'Q5',1,'Open it because familiar contacts cannot send unsafe files.',0),(2,'Q5',2,'Confirm the file through a separate channel before opening it.',1),(2,'Q5',3,'Enable macros so the document can be inspected.',0),(2,'Q5',4,'Upload it to a personal file-sharing site.',0),
(3,'Q1',1,'Collecting information that may help select or approach a target.',1),(3,'Q1',2,'Deleting a victim account after an attack.',0),(3,'Q1',3,'Encrypting a message after it is sent.',0),(3,'Q1',4,'Restoring a password through official support.',0),
(3,'Q2',1,'It guarantees the sender is part of the project.',0),(3,'Q2',2,'It proves the email account is uncompromised.',0),(3,'Q2',3,'It can make a pretext plausible but does not verify identity.',1),(3,'Q2',4,'It means the request has management approval.',0),
(3,'Q3',1,'Using urgency or authority to influence a recipient action.',1),(3,'Q3',2,'Installing a software update from official settings.',0),(3,'Q3',3,'Saving a password in a password manager.',0),(3,'Q3',4,'Checking a sender using a company directory.',0),
(3,'Q4',1,'The password automatically expires before use.',0),(3,'Q4',2,'The attacker may attempt access or reach connected resources.',1),(3,'Q4',3,'The attacker is prevented from accessing any account.',0),(3,'Q4',4,'The account cannot be used to send messages.',0),
(3,'Q5',1,'Reply to the original message with a password question.',0),(3,'Q5',2,'Trust it because project details are accurate.',0),(3,'Q5',3,'Open its link in a private browser window.',0),(3,'Q5',4,'Confirm through a separate trusted channel.',1),
(4,'Q2',1,'Use the new bank details because the deadline is short.',0),(4,'Q2',2,'Verify with contact details already on file and follow approvals.',1),(4,'Q2',3,'Reply to the email asking for a photo ID.',0),(4,'Q2',4,'Send a small test payment without authorization.',0),
(4,'Q3',1,'The bank has approved the page because it has HTTPS.',0),(4,'Q3',2,'The connection is encrypted, but the operator is not authenticated by that fact alone.',1),(4,'Q3',3,'The page cannot collect passwords.',0),(4,'Q3',4,'The displayed bank logo is genuine.',0),
(4,'Q4',1,'It may authorize access or a transaction and must remain private.',1),(4,'Q4',2,'It is public information once sent by SMS.',0),(4,'Q4',3,'It is safe to share with anyone claiming to be support.',0),(4,'Q4',4,'It only confirms the customer email address.',0),
(4,'Q5',1,'Use the link and phone number supplied in the email.',0),(4,'Q5',2,'Ask an unknown caller to verify the alert.',0),(4,'Q5',3,'Open the official bank app or call a known number.',1),(4,'Q5',4,'Search for a random support number in a pop-up.',0),
(5,'Q1',1,'Pay through the text link to avoid losing the parcel.',0),(5,'Q1',2,'Check through the carrier app or independently reached official site.',1),(5,'Q1',3,'Send card details by replying to the text.',0),(5,'Q1',4,'Forward the link to a friend to test it.',0),
(5,'Q2',1,'Phishing conducted through text messaging.',1),(5,'Q2',2,'A secure method of signing in.',0),(5,'Q2',3,'A feature that blocks all spam messages.',0),(5,'Q2',4,'A password storage service.',0),
(5,'Q3',1,'Sender labels cannot ever be changed.',0),(5,'Q3',2,'Familiar threads prove the sender identity.',0),(5,'Q3',3,'Sender details can be spoofed or the account may be abused.',1),(5,'Q3',4,'A phone number is equivalent to a verified website certificate.',0),
(5,'Q4',1,'Send it if the text uses the bank name.',0),(5,'Q4',2,'Do not share it; check the issue using an official bank channel.',1),(5,'Q4',3,'Share it only after asking the sender for a logo.',0),(5,'Q4',4,'Post it in a message to customer support.',0),
(5,'Q5',1,'It proves the sender is a delivery company.',0),(5,'Q5',2,'It means the recipient should pause and verify independently.',1),(5,'Q5',3,'It guarantees the package will be returned.',0),(5,'Q5',4,'It makes the link safe to open.',0),
(6,'Q1',1,'A message sent only by SMS.',0),(6,'Q1',2,'A message tailored to a particular target or organization.',1),(6,'Q1',3,'Any email with an attachment.',0),(6,'Q1',4,'An automatic password reset.',0),
(6,'Q2',1,'It proves the sender controls the colleague identity.',0),(6,'Q2',2,'It shows only that the sender knows or guessed some context.',1),(6,'Q2',3,'It verifies management approval.',0),(6,'Q2',4,'It confirms the message passed every security check.',0),
(6,'Q3',1,'Send the data because the sender knows the project.',0),(6,'Q3',2,'Use the reply address to ask for confirmation.',0),(6,'Q3',3,'Verify through a separate company channel and follow approvals.',1),(6,'Q3',4,'Send only part of the file to see what happens.',0),
(6,'Q4',1,'It can help create a believable pretext for a selected target.',1),(6,'Q4',2,'It automatically blocks phishing messages.',0),(6,'Q4',3,'It proves a sender identity.',0),(6,'Q4',4,'It prevents password reuse.',0),
(6,'Q5',1,'A shared password used by the whole team.',0),(6,'Q5',2,'A unique password and MFA can limit access after exposure.',1),(6,'Q5',3,'Turning off account alerts.',0),(6,'Q5',4,'Approving any sign-in prompt quickly.',0),
(7,'Q2',1,'The site must be genuine because HTTPS is present.',0),(7,'Q2',2,'The logo proves the page was created by the bank.',0),(7,'Q2',3,'Neither feature alone proves the page belongs to the bank.',1),(7,'Q2',4,'The page cannot record credentials.',0),
(7,'Q3',1,'Wait for another message before responding.',0),(7,'Q3',2,'Contact the bank through an official channel promptly.',1),(7,'Q3',3,'Reply with the password to request a reset.',0),(7,'Q3',4,'Use the same password on another account.',0),
(7,'Q4',1,'The code may authorize account access and should not be disclosed.',1),(7,'Q4',2,'The code is not connected to sign-in security.',0),(7,'Q4',3,'The caller already knows the code.',0),(7,'Q4',4,'Sharing the code confirms the caller identity.',0),
(7,'Q5',1,'Use the link in the alert email.',0),(7,'Q5',2,'Open a bank app from an advertisement.',0),(7,'Q5',3,'Use the official app or a number from a card or statement.',1),(7,'Q5',4,'Call a number supplied by an unknown text.',0),
(8,'Q1',1,'To collect credentials for possible account access.',1),(8,'Q1',2,'To encrypt every message in the account.',0),(8,'Q1',3,'To improve the social platform interface.',0),(8,'Q1',4,'To verify a public profile photograph.',0),
(8,'Q2',1,'The same credential may be tried on several services.',1),(8,'Q2',2,'Unique passwords make every service share one password.',0),(8,'Q2',3,'Password reuse automatically enables MFA.',0),(8,'Q2',4,'An attacker cannot test exposed passwords elsewhere.',0),
(8,'Q3',1,'Click quickly so the appeal is submitted before deletion.',0),(8,'Q3',2,'Open the official app independently and check alerts there.',1),(8,'Q3',3,'Enter the password but omit the username.',0),(8,'Q3',4,'Send the password to the message sender.',0),
(8,'Q4',1,'Wait for the attacker to contact you again.',0),(8,'Q4',2,'Use official recovery and warn contacts through another channel.',1),(8,'Q4',3,'Pay an unsolicited account recovery service.',0),(8,'Q4',4,'Reuse the compromised password on a new account.',0),
(8,'Q5',1,'It may receive password reset links for other services.',1),(8,'Q5',2,'It is never used to recover accounts.',0),(8,'Q5',3,'It automatically removes suspicious messages.',0),(8,'Q5',4,'It replaces the need for unique passwords.',0),
(9,'Q2',1,'A breach at one service should not expose the same credential elsewhere.',1),(9,'Q2',2,'All services require identical passwords.',0),(9,'Q2',3,'Unique passwords are easier for attackers to guess.',0),(9,'Q2',4,'Password reuse improves account recovery security.',0),
(9,'Q3',1,'It can generate and store unique passwords for different services.',1),(9,'Q3',2,'It sends passwords to anyone requesting support.',0),(9,'Q3',3,'It guarantees that phishing cannot occur.',0),(9,'Q3',4,'It replaces software updates.',0),
(9,'Q4',1,'A public discussion forum.',0),(9,'Q4',2,'The primary email account that may support account recovery.',1),(9,'Q4',3,'A rarely used game account.',0),(9,'Q4',4,'A temporary guest profile.',0),
(9,'Q5',1,'Keep using it until the next annual update.',0),(9,'Q5',2,'Change it through the trusted service and update reused passwords.',1),(9,'Q5',3,'Reply with the password to confirm the report.',0),(9,'Q5',4,'Disable every security alert.',0),
(10,'Q1',1,'Using two passwords from the same category.',0),(10,'Q1',2,'Combining evidence from distinct authentication categories.',1),(10,'Q1',3,'Signing in without checking identity.',0),(10,'Q1',4,'Using one password on multiple accounts.',0),
(10,'Q2',1,'Both passwords are something the user knows.',1),(10,'Q2',2,'Passwords are possession factors.',0),(10,'Q2',3,'A second password is always a biometric factor.',0),(10,'Q2',4,'Two passwords cannot be used for sign-in.',0),
(10,'Q3',1,'Approve it to stop additional requests.',0),(10,'Q3',2,'Reject it and report suspicious prompts through official support.',1),(10,'Q3',3,'Send the code to the caller.',0),(10,'Q3',4,'Disable MFA using the prompt link.',0),
(10,'Q4',1,'In a public chat with the service provider.',0),(10,'Q4',2,'Securely and separately from the device being recovered.',1),(10,'Q4',3,'In an unprotected note on a shared computer.',0),(10,'Q4',4,'As a reply to an unexpected email.',0),
(10,'Q5',1,'Something the user knows.',0),(10,'Q5',2,'Something the user has, which can support phishing-resistant sign-in.',1),(10,'Q5',3,'A password hint.',0),(10,'Q5',4,'A public account identifier.',0),
(11,'Q1',1,'It encrypts the connection but does not prove the site is legitimate.',1),(11,'Q1',2,'It guarantees the site is operated by a known company.',0),(11,'Q1',3,'It prevents every phishing attempt.',0),(11,'Q1',4,'It verifies all content on the page is accurate.',0),
(11,'Q2',1,'A pop-up from an unfamiliar advertising page.',0),(11,'Q2',2,'The browser built-in update mechanism or official source.',1),(11,'Q2',3,'A link in an unexpected text message.',0),(11,'Q2',4,'A download site recommended by a stranger.',0),
(11,'Q3',1,'Install it because public networks require special updates.',0),(11,'Q3',2,'Close it and check the browser own update settings.',1),(11,'Q3',3,'Disable browser protections and retry.',0),(11,'Q3',4,'Enter account credentials to unlock the update.',0),
(11,'Q4',1,'The actual domain and whether it matches the intended service.',1),(11,'Q4',2,'Whether the page uses a familiar color.',0),(11,'Q4',3,'Whether the page displays a logo.',0),(11,'Q4',4,'Whether the request contains a deadline.',0),
(11,'Q5',1,'Shared computers automatically erase all account data.',0),(11,'Q5',2,'Another user may access saved credentials or an active session.',1),(11,'Q5',3,'Saving passwords disables HTTPS.',0),(11,'Q5',4,'Public computers cannot open websites.',0);

INSERT INTO quiz_options (question_id, option_text, is_correct)
SELECT qq.id, seed.option_text, seed.is_correct
FROM sprint12_new_options AS seed
JOIN sprint12_new_questions AS question_seed
  ON question_seed.lesson_id = seed.lesson_id
 AND question_seed.question_key = seed.question_key
JOIN quizzes AS q ON q.lesson_id = seed.lesson_id
JOIN quiz_questions AS qq
  ON qq.quiz_id = q.id
 AND qq.question_text = question_seed.question_text
WHERE NOT EXISTS (
    SELECT 1 FROM quiz_options AS existing
    WHERE existing.question_id = qq.id
      AND existing.option_text = seed.option_text
);

DROP TEMPORARY TABLE sprint12_new_options;
DROP TEMPORARY TABLE sprint12_new_questions;
DROP TEMPORARY TABLE sprint12_new_quizzes;

COMMIT;
