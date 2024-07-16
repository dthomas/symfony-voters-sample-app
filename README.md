# Symfony Purchase Order Application

This Symfony application demonstrates the use of voters to control access to issuing purchase orders based on the purchase order amount.

## Logic

The application uses a custom voter to determine if a purchase order can be issued based on the amount and the approver:

* Orders less than 10,000 will be approved by Person A.
* Orders between 10,000 and 100,000 will be approved by Person B.
* Orders above 100,000 will be approved by Person C.

The voter ensures that only the correct approver can approve the order based on the specified amount

## How to use

Clone the repo

Create `.env.local` file with following contents:

```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

Run the following commands:

```bash
symfony console doctrine:schema:create
symfony console doctrine:fixture:load --purge-with-truncate
symfony serve
```

The application is available at `https://localhost:8000/`.

Login using the credentials:

```
Username: PersonA
Password: secret
```

PersonA can approve only orders where the value is less than 10,000.

Clicking "Approve Order #1 (Value: 5000)" will display a success message.

Clicking "Approve Order #2 (Value: 15000)" will display a failure message.

## Running Tests

```bash
symfony console doctrine:schema:create --env=test
symfony console doctrine:fixture:load --purge-with-truncate --env=test
bin/phpunit
```