# Banklink

<p>
    <a href="https://github.com/banklink/banklink/actions"><img src="https://img.shields.io/github/actions/workflow/status/banklink/banklink/run-tests.yml?branch=main&label=tests&style=flat-square" alt="Build Status"></a>
    <a href="https://packagist.org/packages/banklink/banklink"><img src="https://img.shields.io/packagist/dt/banklink/banklink.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/banklink/banklink"><img src="https://img.shields.io/packagist/v/banklink/banklink.svg?style=flat-square" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/banklink/banklink"><img src="https://img.shields.io/packagist/l/banklink/banklink.svg?style=flat-square" alt="License"></a>
</p>

Banklink provides an **easy way to integrate with Brazilian banks**, offering a unified interface to access account information, card details, transactions, and statements programmatically.

> **Requires [PHP 8.4+](https://php.net/releases/)**, **[Laravel 11+](https://laravel.com/docs/11.x/)**.

> **Note:** This package interacts with real banking systems. **Use with caution and ensure proper security measures** are in place when handling sensitive financial data.

## Installation

⚡️ Get started by requiring the package using [Composer](https://getcomposer.org):

```bash
composer require banklink/banklink
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag="banklink-config"
```

## Configuration

Configure your bank credentials in your `.env` file:

```env
BANK_BASE_URL=https://internetpf5.itau.com.br
BANK_AGENCY=your_agency
BANK_ACCOUNT=your_account
BANK_ACCOUNT_DIGIT=your_digit
BANK_PASSWORD=your_password
```

## Supported Banks

Currently supported Brazilian banks:

| Bank | Authentication | Accounts | Cards | Transactions | Status |
|------|---------------|----------|-------|--------------|--------|
| **Itaú** | ✅ | ✅ | ✅ | ✅ | Full Support |

*More banks coming soon!*

## Table of Contents
- [Quick Start](#-quick-start)
- [Authentication](#-authentication)
- [Account Information](#-account-information)
- [Card Management](#-card-management)
- [Transactions](#-transactions)
- [Card Statements](#-card-statements)
- [Error Handling](#-error-handling)
- [Security Considerations](#-security-considerations)

### 🚀 Quick Start

```php
use Banklink\Facades\Banklink;

// Authenticate with your bank using your iToken
$bank = Banklink::authenticate('your-itoken-here');

// Get account information
$account = Banklink::account();
echo "Account: {$account->agency}-{$account->number}-{$account->digit}";

// Get all cards
$cards = $account->cards()->all();
foreach ($cards as $card) {
    echo "Card: {$card->name()} (**** {$card->lastFourDigits()})";
}

// Get card by name
$card = $account->cards()->firstWhere('name', '::card_name::');
```

### 🔐 Authentication

Authenticate with your bank using your iToken:

```php
use Banklink\Facades\Banklink;

try {
    $bank = Banklink::authenticate('your-itoken-here');
    // Ready to make API calls
} catch (\Exception $e) {
    echo "Authentication failed: " . $e->getMessage();
}
```

### 🏦 Account Information

Access your account details and balance:

```php
$account = Banklink::account();

echo "Agency: " . $account->agency();
echo "Account: " . $account->number();
echo "Digit: " . $account->digit();
echo "Balance: " . $account->balance(); // If available
```

### 💳 Card Management

Retrieve and manage your credit cards:

```php
// Get all cards
$cards = $account->cards()->all();

// Find specific card
$card = $account->cards()->firstWhere('name', 'UNICLASS BLACK CASHBACK');

// Access card information
echo "Card Name: " . $card->name();
echo "Last 4 digits: " . $card->lastFourDigits();
echo "Brand: " . $card->brand();

// Check card limits
$limits = $card->limit();
echo "Total limit: " . $limits->total();        // "43.656,00"
echo "Available: " . $limits->available();      // "28.516,10"
echo "Used: " . $limits->used();               // "15.139,90"
echo "Usage percentage: " . $limits->usagePercentage() . "%"; // 34.68%

// Check limit status
if ($limits->isNearMax()) {
    echo "Warning: Credit limit nearly maxed out!";
}
```

### 📊 Transactions

Access your checking account transactions:

```php
// Get recent transactions
$transactions = $account->transactions()->recent();

// Get transactions for specific period
$transactions = $account->transactions()->period('2024-01-01', '2024-01-31');

foreach ($transactions as $transaction) {
    echo "Date: " . $transaction->date()->format('Y-m-d');
    echo "Description: " . $transaction->description();
    echo "Amount: " . $transaction->amount();
    echo "Sign: " . $transaction->sign();
    echo "Payment Method: " . $transaction->paymentMethod();
    
    // Check for installments
    if ($installments = $transaction->installments()) {
        echo "Installment: " . $installments->current() . "/" . $installments->total();
    }
}
```

### 🧾 Card Statements

Access your credit card statements and transactions:

```php
// Get card statements
$statements = $card->statements();

foreach ($statements as $statement) {
    echo "Period: " . $statement->period();
    echo "Due Date: " . $statement->dueDate()->format('Y-m-d');
    echo "Amount: " . $statement->amount();
    echo "Status: " . $statement->status()->value;
    
    // Check statement status
    if ($statement->isOverdue()) {
        echo "⚠️ Statement is overdue!";
    }
    
    echo "Days until due: " . $statement->daysUntilDue();
    
    // Get statement holders (for shared cards)
    $holders = $statement->holders();
    foreach ($holders as $holder) {
        echo "Holder: " . $holder->name();
        echo "Card ending: " . $holder->lastFourDigits();
        echo "Amount: " . $holder->amount();
        
        // Get transactions for this holder
        $transactions = $holder->transactions();
        foreach ($transactions as $transaction) {
            echo "  - " . $transaction->description() . ": " . $transaction->amount();
            
            // Show installment info if available
            if ($installments = $transaction->installments()) {
                echo " (" . $installments->current() . "/" . $installments->total() . ")";
            }
        }
    }
}
```

### ⚠️ Error Handling

Always wrap your banking operations in try-catch blocks:

```php
use Banklink\Exceptions\AuthenticationException;
use Banklink\Exceptions\BankException;

try {
    $bank = Banklink::authenticate();
    $account = Banklink::account();
    $cards = $account->cards()->all();
} catch (AuthenticationException $e) {
    // Handle authentication specific errors
    echo "Authentication failed: " . $e->getMessage();
} catch (BankException $e) {
    // Handle general bank API errors
    echo "Bank error: " . $e->getMessage();
} catch (\Exception $e) {
    // Handle unexpected errors
    echo "Unexpected error: " . $e->getMessage();
}
```

### 🔒 Security Considerations

- Never commit credentials to version control
- Always use HTTPS in production
- Encrypt sensitive data at rest
- Log banking operations for auditing
- Implement proper session handling

## Development

### Code Quality

This package maintains high code quality standards:

```bash
# Run tests
composer test

# Run type coverage
composer test:type-coverage

# Check for typos
composer test:typos

# Run static analysis
composer test:types

# Fix code style
composer lint
```

### Contributing

Contributions are welcome! Please ensure:

1. All tests pass
2. Code follows PSR-12 standards
3. Type coverage is maintained at 100%
4. PHPStan analysis passes without errors

## Roadmap

- 🏗️ Support for more Brazilian banks (Bradesco, Banco do Brasil, Santander)
- 📱 Mobile banking integration
- 💰 Investment account support  
- 🔄 Real-time notifications
- 📊 Enhanced transaction categorization
- 🛡️ Advanced security features

## License

**Banklink** was created by **[William Trevisan](https://github.com/williamtrevisan)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
