<?php

namespace App\Exceptions;

use Exception;

/**
 * Marker exception used solely to force a `DB::transaction()` closure to
 * roll back early.
 *
 * Background: in Laravel, `DB::transaction($callback)` only rolls back when
 * the callback THROWS. A bare `return;` inside the closure just exits the
 * closure normally and Laravel commits the transaction as if it succeeded.
 * This exception carries no payload on purpose — the caller already
 * accumulates human-readable messages in an `$errors` array captured by
 * reference from outside the closure, so this exception only needs to be
 * thrown-and-caught to abort the transaction; it is never inspected for
 * its own message.
 */
class StoreIssueTransactionAborted extends Exception
{
}
