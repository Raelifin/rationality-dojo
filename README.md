# rationality-dojo
The website and associated code for rationality-dojo.com

## Auction Algorithm

Dojo practices are selected based on the members' preferences for practices using an auction based on a virtual social currency called "whuffie".  The goal of this algorithm is to select dojo practices taking into account members' preferences and "fairly" allocating between competing desires. This algorithm can be used for any group of people who need to make communal choices.  The explanation below is not specific to dojo.  The dojo implementation can be found in [auctioneer.php](/auctioneer.php).  The rationality-dojo.com site limits bids to -20 to 20 inclusive. 

Each participant has a whuffie balance.  New participants start with a balance of zero.  The total whuffie balance of all participants is always zero, because any credits are offset by debits among other participants.

### Step 0: Prerequisites

Determine possible auction outcomes.  Either an outside source can provide a set of possible outcomes or each participant can propose one or more possible outcomes.

Participants bid.  Each participant places a whuffie bid on each possible outcome.  This will produce a table of bids for each participant and outcome.

### Step 1: Re-floor bids

For each participant, subtract their minimum bid from all their bids.  Their minimum re-floored bid will now be zero.  If the participant's minimum bid was originally zero, this step has no effect.

The purpose of this step is to fix negative bids for later steps and correct for participants who foolishly bid positively on all outcomes.

### Step 2: Scale Bids

Scale each re-floored bid from step 1 by multiplying them by `1/(0.1+e^(-balance/10))`.  Where `balance` is the participant's whuffie balance before the auction.  This is a modified sigmoid function.

### Step 3: Select Outcome

For each outcome, sum the scaled bids from step 2.  Select the outcome with the largest sum.  If multiple outcomes are tied for largest, select between them randomly.  This selection is the "utilitarian" maximum.  That is, the option selected maximizes utility adjusting for past selections as represented in whuffie balances.

### Step 4: Classify Winners & Earners

Classify each participant as either a "winner" or an "earner".  A participant is a winner if and only if the selected outcome from step 3 is their highest bid amount the outcomes, or is tied with it.  All other participants are earners

### Step 5: Distribute Whuffie

This step operates on re-floored bids from step 1. Winners will pay whuffie and earners with receive whuffie.

Let `toGive` equal the sum of the winners' bids for the selected outcome.  This will be their maximum bids.  Let `totalDesire` equal the sum of the `desire` of all earners, where `desire` is an earner's maximum bid less their bid on the selected outcome.  Note that `totalDesire` should be a positive number.  Let `flow` be the minimum of `toGive` and `totalDesire`.  Flow will be the amount of whuffie actually passed from winners to earners.  It is possible for `flow` to be zero if the selected outcome is nobody's favorite or everyone's favorite.

For each winner, subtract from their whuffie balance `give*flow/toGive` where `give` is their bid for the selected outcome (i.e. their maximum) and `flow` and `toGive` are as defined above.

For each earner, add to their whuffie balance `desire*flow/totalDesire` where `desire`, `flow` and `totalDesire` are as defined above.

### Auction Example

The above algorithm is actually quite easy in practice.  An example should make this clear.

Imagine Alice and Bob want to decide what to eat for dinner.  Their whuffie balances are:

|       | Balance |
| ----- | ------: |
| Alice |       2 |
| Bob   |      -2 |

This reflects that fact that Bob "owes" Alice a favor in this domain.

#### Example Step 0: Prerequisites
Alice suggests burgers or pizza.  Bob suggests Chinese food.

Alice and Bob bid:

|       | Burgers | Pizza | Chinese |
| ----- | ------: | ----: | ------: |
| Alice |       1 |     2 |       1 |
| Bob   |      -1 |     2 |       4 |

#### Example Step 1: Re-floor bids

Alice's minimum bid is 1 and Bob's minimum bid is -1.  We subtract those from the bids giving:

|       | Burgers | Pizza | Chinese |
| ----- | ------: | ----: | ------: |
| Alice |       0 |     1 |       0 |
| Bob   |       0 |     3 |       5 |

#### Example Step 2: Scale Bids

Multiply each bid by the modified sigmoid function of the participant's whuffie balance.  This gives:

|       | Burgers | Pizza | Chinese |
| ----- | ------: | ----: | ------: |
| Alice |    0.00 |  1.09 |    0.00 |
| Bob   |    0.00 |  2.27 |    3.78 |

#### Example Step 3: Select Outcome

Summing the bids for each outcome gives:

| Burgers | Pizza | Chinese |
| ------: | ----: | ------: |
|    0.00 |  3.36 |    3.78 |

Chinese is the largest, so Chinese is the selected outcome.

#### Example Step 4: Classify Winners & Earners

Bob bid the most for Chinese food so he is a winner.  Alice did not bid the most for Chinese food so she is an earner.

#### Example Step 5: Distribute Whuffie

`toGive` is 5 because that is Bob's re-floored bid for Chinese food and Bob is the only winner.  `desire` for Alice is 1 because that is the difference between her highest bid of 1 for pizza and her bid of 0 for Chinese food.  `totalDesire` is also 1 since Alice is the only earner.  So `flow` is 1.  Alice loses 1 whuffie.  Bob gains 1 whuffie.  Bringing the whuffie balances to:

|       | Balance |
| ----- | ------: |
| Alice |       1 |
| Bob   |      -1 |
