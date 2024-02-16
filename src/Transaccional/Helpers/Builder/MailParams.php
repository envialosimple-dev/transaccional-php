<?php

namespace EnvialoSimple\Transaccional\Helpers\Builder;

use EnvialoSimple\Transaccional\Exceptions\ESTRException;

class MailParams
{
    protected string $from;
    protected string $to;
    protected ?string $from_name = null;
    protected ?string $to_name = null;
    protected ?string $reply_to;
    protected ?string $preview_text;
    protected ?string $subject;
    protected ?string $html = null;
    protected ?string $text = null;
    protected ?string $template_id = null;
    protected ?array $substitutions = [];
    protected ?array $context = [];
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

    public function getReplyTo(): ?string
    {
        return $this->reply_to;
    }

    public function setReplyTo(string $reply_to): MailParams
    {
        $this->reply_to = $reply_to;

        return $this;
    }

    public function getPreviewText(): ?string
    {
        return $this->preview_text;
    }

    public function setPreviewText(string $preview_text): MailParams
    {
        $this->preview_text = $preview_text;

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

    /**
     * @deprecated Since version 0.0.3. Use getContext instead!
     */
    public function getSubstitutions(): array
    {
        return $this->context;
    }

    /**
     * @deprecated Since version 0.0.3. Use setContext instead!
     */
    public function setSubstitutions(array $substitutions): MailParams
    {
        $this->context = $substitutions;

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext(array $context): MailParams
    {
        $this->context = $context;

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
        // General integrity checks
        if (!isset($this->to)) {
            throw new ESTRException('Email address "To" must be set');
        }

        if (is_null($this->template_id) && is_null($this->text) && is_null($this->html)) {
            throw new ESTRException(
                "No content was provided. \n
                Please set either the text or html attributes, or specify a template ID"
            );
        }

        if (isset($this->template_id) && (isset($this->text) || isset($this->html))) {
            throw new ESTRException('Content (html or text) and templates are mutually exclusive');
        }

        if (!isset($this->template_id)) {
            // Text/HTML related integrity checks
            if (!isset($this->from)) {
                throw new ESTRException('Email address "From" must be set');
            }

            if (!isset($this->subject)) {
                throw new ESTRException('Subject must be set');
            }
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

        if (isset($this->subject)) {
            $result['subject'] = $this->subject;
        }

        if (isset($this->reply_to)) {
            $result['reply_to'] = $this->reply_to;
        }

        if (isset($this->preview_text)) {
            $result['preview_text'] = $this->preview_text;
        }

        if (!is_null($this->template_id)) {
            $result['templateID'] = $this->template_id;
        } else {
            if (!is_null($this->html)) {
                $result['html'] = $this->html;
            }

            if (!is_null($this->text)) {
                $result['text'] = $this->text;
            }
        }

        return array_merge($result, [
            'context' => $this->context,
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
