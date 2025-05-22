# mailerLite-webhook-consumer

A simple PHP webhook consumer for [MailerLite](https://developers.mailerlite.com/docs/webhooks.html) events.

## Features

- Accepts all MailerLite webhook payloads.
- Automatically detects event type from the `"event"` field.
- Processes `subscriber.created` events by creating a `Subscriber` object for further business logic.
- Persists all webhook payloads to the `webhook_data/` directory for audit and debugging.

## Usage

1. Deploy the `mailerlite_webhook_receiver.php` file on your PHP server.
2. Configure your MailerLite webhook to point to its public URL.
3. Ensure the application can write to the `webhook_data/` directory.

## Customization

- To process other event types, add logic to the event detection section.
- To persist data in a database instead of files, replace the file-writing logic with a database call.

## Example payload

See the [MailerLite docs](https://developers.mailerlite.com/docs/webhooks.html) for sample payloads.
