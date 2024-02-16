<?php

namespace Give\EventTickets\Fields;

use Give\Framework\FieldsAPI\Field;

class EventTickets extends Field
{
    protected $title;
    protected $startDateTime;
    protected $description;
    protected $ticketTypes = [];

    const TYPE = 'eventTickets';

    /**
     * @unreleased
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @unreleased
     */
    public function title(string $title): EventTickets
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @unreleased
     */
    public function getStartDateTime(): string
    {
        return $this->startDateTime;
    }

    /**
     * @unreleased
     */
    public function startDateTime(string $date): EventTickets
    {
        $this->startDateTime = $date;
        return $this;
    }

    /**
     * @unreleased
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @unreleased
     */
    public function description(string $description): EventTickets
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @unreleased
     */
    public function getTicketTypes(): array
    {
        return $this->ticketTypes;
    }

    /**
     * @unreleased
     */
    public function ticketTypes(array $ticketTypes): EventTickets
    {
        $this->ticketTypes = $ticketTypes;
        return $this;
    }

    /**
     * @unreleased
     */
    public function getTicketsLabel(): string
    {
        return apply_filters(
            'givewp_event_tickets_block/tickets_label',
            __('Select Tickets', 'give')
        );
    }

    /**
     * @unreleased
     */
    public function getSoldOutMessage(): string
    {
        return apply_filters(
            'givewp_event_tickets_block/sold_out_message',
            __(
                'Thank you for supporting our cause. Our fundraising event tickets are officially sold out. You can still contribute by making a donation.',
                'give'
            )
        );
    }
}
