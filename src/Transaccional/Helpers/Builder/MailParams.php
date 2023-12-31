<?php

namespace EnvialoSimple\Transaccional\Helpers\Builder;

use EnvialoSimple\Transaccional\Exceptions\ESTRException;

class MailParams
{
    protected string $from;
    protected string $to;
    protected ?string $from_name = null;
    protected ?string $to_name = null;
    protected string $subject;
    protected ?string $html = null;
    protected ?string $text = null;
    protected ?string $template_id = null;
    protected ?array $substitutions = [];
    protected ?array $attachments = [];
    protected ?array $variables = [];

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(string $from, ?string $name = null): MailParams
    {
        $this->from = $from;

        if (!is_null($name)) {
            $this->from_name = $name;
        }

        return $this;
    }

    public function getFromName(): ?string
    {
        return $this->from_name;
    }

    public function setFromName(string $from_name): MailParams
    {
        $this->from_name = $from_name;

        return $this;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setTo(string $to, ?string $name = null): MailParams
    {
        $this->to = $to;

        if (!is_null($name)) {
            $this->to_name = $name;
        }

        return $this;
    }

    public function getToName(): ?string
    {
        return $this->to_name;
    }

    public function setToName(string $to_name): MailParams
    {
        $this->to_name = $to_name;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): MailParams
    {
        $this->subject = $subject;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): MailParams
    {
        $this->html = $html;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): MailParams
    {
        $this->text = $text;

        return $this;
    }

    public function getTemplateID(): ?string
    {
        return $this->template_id;
    }

    public function setTemplateID(?string $template_id): MailParams
    {
        $this->template_id = $template_id;

        return $this;
    }

    public function getSubstitutions(): array
    {
        return $this->substitutions;
    }

    public function setSubstitutions(array $substitutions): MailParams
    {
        $this->substitutions = $substitutions;

        return $this;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function setVariables(array $variables): MailParams
    {
        $this->variables = $variables;

        return $this;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function setAttachments(array $attachments): MailParams
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function toArray(): array
    {
        // Integrity checks
        if (!isset($this->to)) {
            throw new ESTRException('Email address "To" must be set');
        }
        if (!isset($this->from)) {
            throw new ESTRException('Email address "From" must be set');
        }
        if (!isset($this->subject)) {
            throw new ESTRException('Subject must be set');
        }
        if (isset($this->template_id) && (isset($this->text) || isset($this->html))) {
            throw new ESTRException('Content (html or text) and templates are mutually exclusive');
        }

        $result = [];

        if (isset($this->from_name)) {
            $result['from'] = [
                'email' => $this->from,
                'name' => $this->from_name,
            ];
        } else {
            $result['from'] = $this->from;
        }

        if (isset($this->to_name)) {
            $result['to'] = [
                'email' => $this->to,
                'name' => $this->to_name,
            ];
        } else {
            $result['to'] = $this->to;
        }

        if (!is_null($this->template_id)) {
            $result['templateID'] = $this->template_id;
        } else {
            $result['html'] = $this->html;
            $result['text'] = $this->text;
        }

        return array_merge($result, [
            'subject' => $this->subject,
            'substitutions' => $this->substitutions,
            'attachments' => $this->attachments,
            'variables' => $this->variables,
        ]);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
