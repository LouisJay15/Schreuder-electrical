<?php
declare(strict_types=1);

/**
 * The entire knowledge the home-screen guide can draw on. Deliberately a
 * flat, hand-written PHP array — not a database query, not an LLM call.
 * This is what makes the two constraints structural rather than promised:
 *   1. "Only Aloe Credit product info"   — there is nothing else IN here.
 *   2. "Never share private information" — this file never touches the
 *      users/applications/contact_messages tables, so there is no code
 *      path through which an account, application, or internal record
 *      could ever appear in a reply, regardless of what is asked.
 * Keep every fact here consistent with the rest of the site (services.php,
 * loan-calculator.php, responsible-lending.php) — this is the one place
 * copy drifts silently if it isn't kept in sync.
 */
function guide_topics(): array
{
    return [
        [
            'id' => 'loan_amount',
            'keywords' => ['how much', 'borrow', 'maximum', 'minimum', 'loan amount', 'loan size', 'much can i'],
            'answer' => "Aloe Credit offers personal loans from R1,000 to R250,000. The exact amount you're offered depends on your affordability assessment.",
            'cta' => ['label' => 'Check your amount', 'href' => '/apply'],
            'suggestions' => ['What are your interest rates?', 'How long can I take to repay?'],
        ],
        [
            'id' => 'rates',
            'keywords' => ['rate', 'interest', 'percent', '%', 'apr', 'fee', 'fees', 'cost', 'expensive', 'cheap'],
            'answer' => "Your rate depends on your credit profile and affordability, and is capped under the National Credit Act. On top of interest, the only other charges are a once-off initiation fee and a monthly service fee — both capped by law and shown in full before you accept any offer.",
            'cta' => ['label' => 'Try the calculator', 'href' => '/loan-calculator'],
            'suggestions' => ['How much can I borrow?', 'Are there hidden fees?'],
        ],
        [
            'id' => 'term',
            'keywords' => ['term', 'months', 'how long to repay', 'repayment period', 'duration'],
            'answer' => "You can repay over 6 to 60 months, in fixed monthly instalments, so your budget stays predictable.",
            'cta' => ['label' => 'See a repayment example', 'href' => '/loan-calculator'],
            'suggestions' => ['Can I settle early?', 'What are your interest rates?'],
        ],
        [
            'id' => 'apply_process',
            'keywords' => ['apply', 'application', 'sign up', 'get started', 'how do i start'],
            'answer' => "Applying takes about two minutes: tell us your income and the amount you need, and we run a pre-qualification check that never affects your credit score. Most applicants get a decision within one business day.",
            'cta' => ['label' => 'Start your application', 'href' => '/apply'],
            'suggestions' => ['What do I need to apply?', 'How long does approval take?'],
        ],
        [
            'id' => 'eligibility',
            'keywords' => ['eligible', 'qualify', 'requirements', 'documents', 'need to apply', 'who can apply'],
            'answer' => "You need to be a South African resident, at least 18 years old, and legally able to enter a credit agreement. If your application is pre-qualified, we'll then ask for a payslip and bank statements to confirm affordability.",
            'cta' => ['label' => 'Check your eligibility', 'href' => '/apply'],
            'suggestions' => ['How long does approval take?', 'Is Aloe Credit registered with the NCR?'],
        ],
        [
            'id' => 'approval_time',
            'keywords' => ['how long', 'decision', 'approve', 'wait', 'fast', 'quick', 'when will i know'],
            'answer' => "Most applicants hear back within one business day of submitting a complete application.",
            'cta' => ['label' => 'Apply now', 'href' => '/apply'],
            'suggestions' => ['What do I need to apply?', 'How much can I borrow?'],
        ],
        [
            'id' => 'security',
            'keywords' => ['secure', 'safe', 'privacy', 'popia', 'password', '2fa', 'two-step', 'hack', 'protect my data'],
            'answer' => "Your data is encrypted in transit, passwords are hashed and never stored in plain text, and every login is protected by a one-time email code. We only ever collect what's needed to assess a loan — full details are in our privacy policy.",
            'cta' => ['label' => 'Read the privacy policy', 'href' => '/privacy-policy'],
            'suggestions' => ['Is Aloe Credit registered with the NCR?', 'How do I apply?'],
        ],
        [
            'id' => 'ncr',
            'keywords' => ['ncr', 'registered', 'license', 'regulator', 'legit', 'legal', 'real company'],
            'answer' => "Yes — Aloe Credit (Pty) Ltd is registered with the National Credit Regulator and operates under the National Credit Act 34 of 2005.",
            'cta' => ['label' => 'Responsible lending details', 'href' => '/responsible-lending'],
            'suggestions' => ['How do I lodge a complaint?', 'What are your interest rates?'],
        ],
        [
            'id' => 'early_settlement',
            'keywords' => ['settle early', 'early settlement', 'pay off early', 'settle my loan', 'pay it off faster'],
            'answer' => "You can settle your loan early at any time with no penalty — you'll only pay interest up to the date you settle.",
            'cta' => ['label' => 'See the loan products', 'href' => '/services'],
            'suggestions' => ['What are your interest rates?', 'How do I apply?'],
        ],
        [
            'id' => 'missed_payment',
            'keywords' => ['miss a payment', 'late payment', "can't pay", 'struggling', 'debt review', 'over-indebted', 'behind on'],
            'answer' => "Reach out before you miss a payment — we'd rather help restructure it than let it default. If credit generally feels unmanageable, you also have the right to apply for debt review with a registered debt counsellor.",
            'cta' => ['label' => 'Get help now', 'href' => '/responsible-lending'],
            'suggestions' => ['How do I contact Aloe Credit?', 'Can I settle early?'],
        ],
        [
            'id' => 'consolidation',
            'keywords' => ['consolidate', 'consolidation', 'combine debt', 'multiple accounts', 'combine loans'],
            'answer' => "Debt consolidation rolls store cards, credit cards, and other loans into one loan with a single monthly payment — often at a lower blended rate.",
            'cta' => ['label' => 'Explore consolidation', 'href' => '/services'],
            'suggestions' => ['How much can I borrow?', 'What are your interest rates?'],
        ],
        [
            'id' => 'credit_score',
            'keywords' => ['credit score', 'improve my score', 'credit report'],
            'answer' => "Pre-qualifying with Aloe Credit never affects your credit score — only a full application does. We've also written a short guide on building a stronger score over time.",
            'cta' => ['label' => 'Read the guide', 'href' => '/blog/how-to-improve-your-credit-score-in-south-africa'],
            'suggestions' => ['How do I apply?', 'What are your interest rates?'],
        ],
        [
            'id' => 'contact_human',
            'keywords' => ['talk to someone', 'human', 'speak to', 'call you', 'phone number', 'email address', 'real person'],
            'answer' => "You can reach our team at hello@aloecredit.co.za or 0800 256 327, Mon-Fri 8am-5pm SAST.",
            'cta' => ['label' => 'Contact us', 'href' => '/contact'],
            'suggestions' => ['How do I apply?', 'Is Aloe Credit registered with the NCR?'],
        ],
    ];
}

/**
 * Anything asking for account-specific, internal, or otherwise private
 * information is intercepted before topic matching runs at all — this
 * guard is checked first and wins regardless of what else the message
 * contains.
 */
function guide_private_data_patterns(): array
{
    return [
        'my account', 'my application', 'my balance', 'my password', 'my details',
        'reset my', 'other customer', 'someone else', "another user", 'employee', 'staff member',
        'admin panel', 'admin dashboard', 'database', 'internal', 'confidential',
        'ignore previous', 'ignore your instructions', 'system prompt', 'you are now', 'act as',
    ];
}

function guide_private_data_response(): array
{
    return [
        'reply' => "I can't look up personal account, application, or internal company information here — this guide only answers general questions about Aloe Credit's products. To check your own account, please log in; for anything else, our team can help directly.",
        'cta' => ['label' => 'Log in', 'href' => '/login'],
        'suggestions' => ['How do I contact Aloe Credit?', 'How do I apply?'],
    ];
}

function guide_fallback_response(): array
{
    return [
        'reply' => "I can only help with questions about Aloe Credit's loans and application process. Try asking about loan amounts, interest rates, eligibility, how to apply, or security.",
        'cta' => null,
        'suggestions' => ['How much can I borrow?', 'How do I apply?', 'Is my information secure?'],
    ];
}

/**
 * Plain keyword-overlap scoring — no external calls, fully deterministic,
 * trivial to audit. Picks the topic whose keyword list matches the most
 * substrings in the visitor's message.
 */
function guide_match(string $message): array
{
    $normalized = ' ' . mb_strtolower($message) . ' ';

    foreach (guide_private_data_patterns() as $pattern) {
        if (str_contains($normalized, $pattern)) {
            return guide_private_data_response();
        }
    }

    $best = null;
    $bestScore = 0;
    foreach (guide_topics() as $topic) {
        $score = 0;
        foreach ($topic['keywords'] as $kw) {
            if (str_contains($normalized, mb_strtolower($kw))) {
                $score++;
            }
        }
        if ($score > $bestScore) {
            $bestScore = $score;
            $best = $topic;
        }
    }

    if ($best === null) {
        return guide_fallback_response();
    }

    return [
        'reply' => $best['answer'],
        'cta' => $best['cta'],
        'suggestions' => $best['suggestions'],
    ];
}
