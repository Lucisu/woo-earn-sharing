# Woo Earn Sharing

Automatically generate codes for your users to be able to share with friends
When a code is used, and the request is given as “Completed”, the code owner will receive a percentage (set in the dashboard admin of WordPress), which will be in your balance
The balance will be used on the next purchase.

### Feature:
You can enable returning the money to a user who used your balance but that the purchase was refunded, canceled or failed!

##### Note:
If a user makes a purchase that is less than their balance, ie their balance is greater than what they are trying to buy, the purchase amount will be 1, to avoid problems with payment methods when trying to finalize a purchase costing 0.00.

##### Example:
User Balance: USD 100.00
User Cart: USD 50.00
Discount based on balance: USD 49.00
Total purchase: USD 1.00

And then the user will have a USD 51.00 balance.

### Shortcodes!
**[wooes_user_balance]** – Show current user balance
**[wooes_user_code]** – Show current user code

