# GPC Library - AI Agent Documentation

> **Agent Protocol: Keep This Document Updated**
> All agents are required to update this document with any new, relevant information discovered during their work on the GPC library. This includes changes in payment processing functionality, new bank flavors, updated parsing logic, or newly established payment patterns.

This document provides comprehensive technical documentation for the GPC library, a PHP payment processing and bank statement parsing utility used in the Hnutí DUHA IS application. The library handles GPC (General Payment Code) file parsing, bank statement processing, and transaction extraction.

---

## Table of Contents

1. [Library Overview](#1-library-overview)
2. [Core Architecture](#2-core-architecture)
3. [GPC File Format](#3-gpc-file-format)
4. [Bank Flavors](#4-bank-flavors)
5. [Statement Processing](#5-statement-processing)
6. [Transaction Processing](#6-transaction-processing)
7. [Data Extraction](#7-data-extraction)
8. [Common Patterns](#8-common-patterns)
9. [Troubleshooting](#9-troubleshooting)
10. [Development Guidelines](#10-development-guidelines)
11. [API Reference](#11-api-reference)

---

## 1. Library Overview

### 1.1. Purpose

The GPC library is responsible for:

- **GPC File Parsing**: Parse GPC (General Payment Code) bank statement files
- **Bank Statement Processing**: Extract and process bank statement data
- **Transaction Extraction**: Parse individual transactions from statements
- **Multi-Bank Support**: Support for different bank formats (CSAS, FIO, General)
- **Data Normalization**: Standardize data across different bank formats
- **Account Number Decoding**: Decode bank-specific account number formats

### 1.2. Key Features

- **GPC Format Support**: Full support for GPC bank statement format
- **Multi-Bank Support**: CSAS, FIO, and General bank flavors
- **Transaction Processing**: Complete transaction data extraction
- **Account Decoding**: Bank-specific account number decoding
- **Currency Support**: Multi-currency transaction support
- **File Processing**: Both string and file-based parsing

### 1.3. Core Components

- **Parser**: Main parsing engine for GPC files
- **Flavor**: Bank-specific processing logic
- **Statement**: Bank statement container
- **Transaction**: Individual transaction processing
- **Header**: Statement header information
- **Exchange**: Transaction exchange data
- **Detail**: Transaction detail information

### 1.4. Dependencies

- **Katu Framework**: Base framework for file handling and utilities
- **Pankki Library**: Banking integration for account numbers and currencies
- **TString**: String processing and normalization

---

## 2. Core Architecture

### 2.1. Parser Class

**Location**: `src/Parser.php`

Main parsing engine for GPC files:

```php
class Parser
{
    public function createStringParse(Flavor $flavor, string $string): StringParse
    {
        return new StringParse($flavor, $string);
    }
    
    public function createFileParse(Flavor $flavor, \Katu\Files\File $file): FileParse
    {
        return new FileParse($flavor, $file);
    }
}
```

**Key Features**:
- **String Parsing**: Parse GPC data from strings
- **File Parsing**: Parse GPC data from files
- **Flavor Support**: Bank-specific processing
- **Unified Interface**: Consistent parsing interface

### 2.2. Flavor System

#### Flavor Base Class

```php
abstract class Flavor
{
    abstract public function getTitle(): string;
    
    public function getDecodedAccountNumber(string $accountId): string
    {
        return $accountId;
    }
    
    public function getDecodedCurrencyId(string $currencyId): string
    {
        return $currencyId;
    }
}
```

#### Bank-Specific Flavors

- **CSAS**: Česká spořitelna specific processing
- **FIO**: FIO banka specific processing
- **General**: Generic processing for other banks

### 2.3. Data Processing Pipeline

#### Parsing Flow

```
GPC File/String → Parser → Flavor → Statement → Transactions
```

#### Data Extraction

```
Statement → Header + Transactions
Transaction → Exchange + Detail + Messages
```

---

## 3. GPC File Format

### 3.1. GPC Format Overview

GPC (General Payment Code) is a standardized format for bank statements used in the Czech Republic. It provides:

- **Standardized Structure**: Consistent format across banks
- **Transaction Data**: Complete transaction information
- **Account Information**: Account details and balances
- **Multi-Currency Support**: Support for different currencies
- **Bank-Specific Extensions**: Bank-specific data encoding

### 3.2. File Structure

#### Statement Structure

```
Header Line (Account Information)
├── Transaction 1
│   ├── Exchange Line
│   ├── Detail Line
│   ├── Message 1 Line
│   └── Message 2 Line
├── Transaction 2
│   └── ...
└── Transaction N
```

#### Line Types

- **Header**: Account information and balances
- **Exchange**: Transaction exchange data
- **Detail**: Transaction detail information
- **Message1**: First message line
- **Message2**: Second message line

### 3.3. Data Encoding

#### Account Numbers

- **16-digit format**: Padded with leading zeros
- **Bank-specific encoding**: Different banks use different encoding
- **Prefix support**: Account prefixes for sub-accounts

#### Currency Codes

- **5-digit format**: Standardized currency identifiers
- **CZK support**: Czech Koruna (00203)
- **Multi-currency**: Support for various currencies

#### Amounts

- **14-digit format**: 12 digits for whole number, 2 for decimals
- **CZK in haléře**: Czech Koruna amounts in haléře (1/100 of CZK)
- **Sign handling**: Positive/negative amounts

---

## 4. Bank Flavors

### 4.1. CSAS (Česká spořitelna)

#### CSAS Flavor

```php
class CSAS extends General
{
    public function getTitle(): string
    {
        return "Česká spořitelna";
    }
    
    public function getDecodedAccountNumber(string $accountId): string
    {
        // CSAS-specific account number decoding
        return implode([
            $accountId[10], $accountId[11], $accountId[12], $accountId[13], $accountId[14], $accountId[15],
            $accountId[4], $accountId[5], $accountId[6], $accountId[7], $accountId[8], $accountId[3],
            $accountId[9], $accountId[1], $accountId[2], $accountId[0],
        ]);
    }
    
    public function getDecodedCurrencyId(string $currencyId): string
    {
        return "00203"; // CZK
    }
}
```

#### CSAS Account Number Decoding

CSAS uses a specific permutation for account numbers:

- **Internal format**: C0C8C9C6C1C2C3C4C5C7P1P2P3P4P5P6
- **Display format**: P1P2P3P4P5P6C1C2C3C4C5C6C7C8C9C0
- **Decoding**: Reorder characters according to CSAS specification

### 4.2. FIO Banka

#### FIO Flavor

```php
class FIO extends CSAS
{
    public function getTitle(): string
    {
        return "FIO banka";
    }
}
```

#### FIO Processing

- **Inherits from CSAS**: Uses same account number decoding
- **FIO-specific**: May have additional FIO-specific processing
- **Same format**: Uses GPC format with CSAS decoding

### 4.3. General Flavor

#### General Flavor

```php
class General extends Flavor
{
    public function getTitle(): string
    {
        return "Obecný";
    }
}
```

#### General Processing

- **Generic processing**: No bank-specific decoding
- **Standard format**: Uses standard GPC format
- **Fallback**: Used when bank-specific flavor is not available

---

## 5. Statement Processing

### 5.1. Statement Class

#### Statement Structure

```php
class Statement
{
    protected $lines;
    
    public function getFlavor(): Flavor
    {
        return $this->getHeader()->getFlavor();
    }
    
    public function getHeader(): Header
    {
        return new Header($this->getLines()->filterHeaderLines()->getFirst());
    }
    
    public function getTransactions(): TransactionCollection
    {
        // Process transactions from lines
    }
}
```

#### Statement Processing

```php
// Create statement from GPC data
$parser = new Parser();
$stringParse = $parser->createStringParse($flavor, $gpcString);
$statements = $stringParse->getStatements();

foreach ($statements as $statement) {
    $header = $statement->getHeader();
    $transactions = $statement->getTransactions();
}
```

### 5.2. Header Processing

#### Header Information

```php
class Header extends LineContainer
{
    // Account number (16 digits)
    public function getAccountNumber(): string;
    
    // Account name (20 characters)
    public function getAccountName(): string;
    
    // Start balance date (DDMMRR)
    public function getStartBalanceDate(): string;
    
    // Start balance (14 digits)
    public function getStartBalance(): string;
    
    // End balance (14 digits)
    public function getEndBalance(): string;
    
    // Debit amount (14 digits)
    public function getDebitAmount(): string;
    
    // Credit amount (14 digits)
    public function getCreditAmount(): string;
    
    // Statement ID (3 digits)
    public function getStatementId(): string;
    
    // Statement date (DDMMRR)
    public function getStatementDate(): string;
}
```

#### Header Usage

```php
$header = $statement->getHeader();

// Get account information
$accountNumber = $header->getAccountNumber();
$accountName = $header->getAccountName();

// Get balance information
$startBalance = $header->getStartBalance();
$endBalance = $header->getEndBalance();

// Get statement information
$statementId = $header->getStatementId();
$statementDate = $header->getStatementDate();
```

---

## 6. Transaction Processing

### 6.1. Transaction Class

#### Transaction Structure

```php
class Transaction
{
    protected $statement;
    protected $lines;
    
    public function getExchange(): Exchange;
    public function getDetail(): Detail;
    public function getMessage1(): Message1;
    public function getMessage2(): Message2;
}
```

#### Transaction Processing

```php
$transactions = $statement->getTransactions();

foreach ($transactions as $transaction) {
    $exchange = $transaction->getExchange();
    $detail = $transaction->getDetail();
    $message1 = $transaction->getMessage1();
    $message2 = $transaction->getMessage2();
}
```

### 6.2. Exchange Data

#### Exchange Information

```php
class Exchange extends LineContainer
{
    // Creditor account number (16 digits)
    public function getCreditorAccountNumber(): string;
    
    // Debtor account number (16 digits)
    public function getDebtorAccountNumber(): string;
    
    // Receipt ID (13 digits)
    public function getReceiptId(): string;
    
    // Amount (12 digits)
    public function getAmount(): string;
    
    // Accounting kind (1 digit)
    public function getAccountingKind(): string;
    
    // Variable symbol (10 digits)
    public function getVariableSymbol(): string;
    
    // Bank code (4 digits)
    public function getBankCode(): string;
    
    // Constant symbol (4 digits)
    public function getConstantSymbol(): string;
    
    // Specific symbol (10 digits)
    public function getSpecificSymbol(): string;
    
    // Conversion date (DDMMRR)
    public function getConversionDate(): string;
    
    // Debtor name (20 characters)
    public function getDebtorName(): string;
    
    // Currency ID (5 digits)
    public function getCurrencyId(): string;
    
    // Date (DDMMRR)
    public function getDate(): string;
}
```

#### Exchange Usage

```php
$exchange = $transaction->getExchange();

// Get account information
$creditorAccount = $exchange->getCreditorAccountNumber();
$debtorAccount = $exchange->getDebtorAccountNumber();

// Get transaction details
$amount = $exchange->getAmount();
$variableSymbol = $exchange->getVariableSymbol();
$constantSymbol = $exchange->getConstantSymbol();
$specificSymbol = $exchange->getSpecificSymbol();

// Get dates
$date = $exchange->getDate();
$conversionDate = $exchange->getConversionDate();
```

### 6.3. Transaction Data Extraction

#### Transaction Properties

```php
// Get transaction date
$date = $transaction->getDate();

// Get debtor account
$debtorAccount = $transaction->getDebtorAccount();

// Get debtor name
$debtorName = $transaction->getDebtorName();

// Get creditor account number
$creditorAccountNumber = $transaction->getCreditorAccountNumber();

// Get transaction amount
$worth = $transaction->getWorth();

// Get payment symbols
$variableSymbol = $transaction->getVariableSymbol();
$constantSymbol = $transaction->getConstantSymbol();
$specificSymbol = $transaction->getSpecificSymbol();
```

#### Transaction Processing

```php
foreach ($transactions as $transaction) {
    // Get basic information
    $date = $transaction->getDate();
    $debtorName = $transaction->getDebtorName();
    $worth = $transaction->getWorth();
    
    // Get account information
    $debtorAccount = $transaction->getDebtorAccount();
    $creditorAccountNumber = $transaction->getCreditorAccountNumber();
    
    // Get payment symbols
    $variableSymbol = $transaction->getVariableSymbol();
    $constantSymbol = $transaction->getConstantSymbol();
    $specificSymbol = $transaction->getSpecificSymbol();
    
    // Process transaction
    processTransaction($transaction);
}
```

---

## 7. Data Extraction

### 7.1. String Parsing

#### StringParse Class

```php
class StringParse
{
    protected $flavor;
    protected $string;
    
    public function getLines(): LineCollection
    {
        $array = preg_split("/\n/", $this->getString());
        return new LineCollection(array_map(function (string $string, int $index) {
            return new Line($this->getFlavor(), $index + 1, $string);
        }, $array, array_keys($array)));
    }
    
    public function getStatements(): StatementCollection
    {
        return $this->getLines()->getStatements();
    }
}
```

#### String Parsing Usage

```php
// Parse GPC string
$parser = new Parser();
$flavor = new \GPC\Flavors\CSAS();
$stringParse = $parser->createStringParse($flavor, $gpcString);

// Get statements
$statements = $stringParse->getStatements();

// Process statements
foreach ($statements as $statement) {
    $transactions = $statement->getTransactions();
    // Process transactions
}
```

### 7.2. File Parsing

#### FileParse Class

```php
class FileParse
{
    protected $flavor;
    protected $file;
    
    public function getStringParse(): StringParse
    {
        return new StringParse($this->getFlavor(), iconv("Windows-1250", "UTF-8", $this->getFile()->get()));
    }
}
```

#### File Parsing Usage

```php
// Parse GPC file
$parser = new Parser();
$flavor = new \GPC\Flavors\CSAS();
$file = new \Katu\Files\File($filePath);
$fileParse = $parser->createFileParse($flavor, $file);

// Get string parse
$stringParse = $fileParse->getStringParse();

// Get statements
$statements = $stringParse->getStatements();
```

### 7.3. Data Processing Pipeline

#### Complete Processing

```php
// Complete GPC processing pipeline
function processGPCFile(string $filePath, Flavor $flavor): array
{
    $parser = new Parser();
    $file = new \Katu\Files\File($filePath);
    $fileParse = $parser->createFileParse($flavor, $file);
    $stringParse = $fileParse->getStringParse();
    $statements = $stringParse->getStatements();
    
    $results = [];
    foreach ($statements as $statement) {
        $header = $statement->getHeader();
        $transactions = $statement->getTransactions();
        
        $results[] = [
            'header' => [
                'accountNumber' => $header->getAccountNumber(),
                'accountName' => $header->getAccountName(),
                'startBalance' => $header->getStartBalance(),
                'endBalance' => $header->getEndBalance(),
                'statementDate' => $header->getStatementDate()
            ],
            'transactions' => array_map(function($transaction) {
                return [
                    'date' => $transaction->getDate(),
                    'debtorName' => $transaction->getDebtorName(),
                    'worth' => $transaction->getWorth(),
                    'variableSymbol' => $transaction->getVariableSymbol(),
                    'constantSymbol' => $transaction->getConstantSymbol(),
                    'specificSymbol' => $transaction->getSpecificSymbol()
                ];
            }, $transactions->getArrayCopy())
        ];
    }
    
    return $results;
}
```

---

## 8. Common Patterns

### 8.1. Basic GPC Processing

```php
// Basic GPC processing
$parser = new Parser();
$flavor = new \GPC\Flavors\CSAS();
$stringParse = $parser->createStringParse($flavor, $gpcString);
$statements = $stringParse->getStatements();

foreach ($statements as $statement) {
    $transactions = $statement->getTransactions();
    // Process transactions
}
```

### 8.2. Bank-Specific Processing

```php
// CSAS processing
$csasFlavor = new \GPC\Flavors\CSAS();
$stringParse = $parser->createStringParse($csasFlavor, $gpcString);

// FIO processing
$fioFlavor = new \GPC\Flavors\FIO();
$stringParse = $parser->createStringParse($fioFlavor, $gpcString);

// General processing
$generalFlavor = new \GPC\Flavors\General();
$stringParse = $parser->createStringParse($generalFlavor, $gpcString);
```

### 8.3. Transaction Filtering

```php
// Filter transactions by date
$transactions = $statement->getTransactions();
$filteredTransactions = array_filter($transactions->getArrayCopy(), function($transaction) {
    $date = $transaction->getDate();
    return $date && $date->isAfter($startDate) && $date->isBefore($endDate);
});
```

### 8.4. Amount Processing

```php
// Process transaction amounts
foreach ($transactions as $transaction) {
    $worth = $transaction->getWorth();
    $amount = $worth->getAmount();
    $currency = $worth->getCurrency();
    
    // Convert to CZK if needed
    if ($currency->getCode() !== 'CZK') {
        $amountInCZK = convertCurrency($amount, $currency, 'CZK');
    }
}
```

### 8.5. Account Processing

```php
// Process account information
foreach ($transactions as $transaction) {
    $debtorAccount = $transaction->getDebtorAccount();
    $creditorAccountNumber = $transaction->getCreditorAccountNumber();
    
    // Get account details
    $accountNumber = $debtorAccount->getAccountNumber();
    $bankCode = $debtorAccount->getBankCode();
    
    // Process account
    processAccount($debtorAccount);
}
```

---

## 9. Troubleshooting

### 9.1. Common Issues

#### File Encoding Issues

- Check file encoding (Windows-1250 to UTF-8)
- Verify file format and structure
- Handle encoding conversion properly
- Check for BOM or special characters

#### Parsing Issues

- Verify GPC format compliance
- Check line structure and formatting
- Handle missing or malformed lines
- Validate data integrity

#### Bank-Specific Issues

- Verify bank flavor selection
- Check account number decoding
- Handle bank-specific formats
- Validate currency codes

### 9.2. Debugging

#### Statement Debugging

```php
// Debug statement information
$statement = $statements->getFirst();
$header = $statement->getHeader();

echo "Account Number: " . $header->getAccountNumber() . "\n";
echo "Account Name: " . $header->getAccountName() . "\n";
echo "Start Balance: " . $header->getStartBalance() . "\n";
echo "End Balance: " . $header->getEndBalance() . "\n";
echo "Statement Date: " . $header->getStatementDate() . "\n";
```

#### Transaction Debugging

```php
// Debug transaction information
$transaction = $transactions->getFirst();
$exchange = $transaction->getExchange();

echo "Date: " . $transaction->getDate() . "\n";
echo "Amount: " . $transaction->getWorth() . "\n";
echo "Variable Symbol: " . $transaction->getVariableSymbol() . "\n";
echo "Debtor Name: " . $transaction->getDebtorName() . "\n";
```

#### Flavor Debugging

```php
// Debug flavor information
$flavor = new \GPC\Flavors\CSAS();
echo "Flavor: " . $flavor->getTitle() . "\n";

// Test account number decoding
$accountId = "1234567890123456";
$decoded = $flavor->getDecodedAccountNumber($accountId);
echo "Original: {$accountId}\n";
echo "Decoded: {$decoded}\n";
```

---

## 10. Development Guidelines

### 10.1. Library Development

**Requirements**:
- Implement proper GPC format parsing
- Support multiple bank flavors
- Handle account number decoding
- Implement transaction processing

### 10.2. Bank Flavor Development

**Best Practices**:
- Extend base Flavor class
- Implement bank-specific decoding
- Handle currency codes properly
- Test with real bank data

### 10.3. Data Processing

**Guidelines**:
- Validate data integrity
- Handle missing data gracefully
- Implement proper error handling
- Support multiple currencies

### 10.4. Performance Optimization

**Optimization Strategies**:
- Use efficient string processing
- Implement proper memory management
- Handle large files efficiently
- Optimize data extraction

---

## 11. API Reference

### 11.1. Parser Class

```php
class Parser
{
    public function createStringParse(Flavor $flavor, string $string): StringParse;
    public function createFileParse(Flavor $flavor, \Katu\Files\File $file): FileParse;
}
```

### 11.2. Flavor Classes

```php
abstract class Flavor
{
    abstract public function getTitle(): string;
    public function getDecodedAccountNumber(string $accountId): string;
    public function getDecodedCurrencyId(string $currencyId): string;
}

class CSAS extends General
{
    public function getTitle(): string;
    public function getDecodedAccountNumber(string $accountId): string;
    public function getDecodedCurrencyId(string $currencyId): string;
}

class FIO extends CSAS
{
    public function getTitle(): string;
}

class General extends Flavor
{
    public function getTitle(): string;
}
```

### 11.3. Statement Classes

```php
class Statement
{
    public function getFlavor(): Flavor;
    public function getHeader(): Header;
    public function getTransactions(): TransactionCollection;
    public function addLine(Line $line): Statement;
    public function getLines(): LineCollection;
}

class Header extends LineContainer
{
    public function getAccountNumber(): string;
    public function getAccountName(): string;
    public function getStartBalanceDate(): string;
    public function getStartBalance(): string;
    public function getEndBalance(): string;
    public function getDebitAmount(): string;
    public function getCreditAmount(): string;
    public function getStatementId(): string;
    public function getStatementDate(): string;
}
```

### 11.4. Transaction Classes

```php
class Transaction
{
    public function getExchange(): Exchange;
    public function getDetail(): Detail;
    public function getMessage1(): Message1;
    public function getMessage2(): Message2;
    public function getDate(): ?Time;
    public function getDebtorAccount(): Account;
    public function getDebtorName(): string;
    public function getCreditorAccountNumber(): AccountNumber;
    public function getWorth(): Worth;
    public function getVariableSymbol(): VariableSymbol;
    public function getConstantSymbol(): string;
    public function getSpecificSymbol(): string;
}

class Exchange extends LineContainer
{
    public function getCreditorAccountNumber(): string;
    public function getDebtorAccountNumber(): string;
    public function getReceiptId(): string;
    public function getAmount(): string;
    public function getAccountingKind(): string;
    public function getVariableSymbol(): string;
    public function getBankCode(): string;
    public function getConstantSymbol(): string;
    public function getSpecificSymbol(): string;
    public function getConversionDate(): string;
    public function getDebtorName(): string;
    public function getCurrencyId(): string;
    public function getDate(): string;
}
```

### 11.5. Parse Classes

```php
class StringParse
{
    public function __construct(Flavor $flavor, string $string);
    public function getLines(): LineCollection;
    public function getStatements(): StatementCollection;
}

class FileParse
{
    public function __construct(Flavor $flavor, \Katu\Files\File $file);
    public function getStringParse(): StringParse;
}
```

### 11.6. Usage Examples

#### Basic Usage

```php
// Parse GPC string
$parser = new Parser();
$flavor = new \GPC\Flavors\CSAS();
$stringParse = $parser->createStringParse($flavor, $gpcString);
$statements = $stringParse->getStatements();
```

#### File Processing

```php
// Parse GPC file
$parser = new Parser();
$flavor = new \GPC\Flavors\CSAS();
$file = new \Katu\Files\File($filePath);
$fileParse = $parser->createFileParse($flavor, $file);
$stringParse = $fileParse->getStringParse();
$statements = $stringParse->getStatements();
```

#### Transaction Processing

```php
// Process transactions
foreach ($statements as $statement) {
    $transactions = $statement->getTransactions();
    
    foreach ($transactions as $transaction) {
        $date = $transaction->getDate();
        $amount = $transaction->getWorth();
        $variableSymbol = $transaction->getVariableSymbol();
        $debtorName = $transaction->getDebtorName();
        
        // Process transaction
        processTransaction($transaction);
    }
}
```

This comprehensive documentation covers the GPC library, providing AI agents with detailed information about GPC file parsing, bank statement processing, transaction extraction, and multi-bank support.
