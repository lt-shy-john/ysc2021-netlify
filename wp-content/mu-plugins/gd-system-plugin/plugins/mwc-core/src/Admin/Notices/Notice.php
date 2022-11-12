<?php

namespace GoDaddy\WordPress\MWC\Core\Admin\Notices;

use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Models\User;
use GoDaddy\WordPress\MWC\Common\Traits\CanGetNewInstanceTrait;

/**
 * The model representation of an admin notice.
 */
class Notice
{
    use CanGetNewInstanceTrait;

    /** @var string represents the error admin type */
    const TYPE_ERROR = 'error';

    /** @var string represents the notice admin type */
    const TYPE_INFO = 'info';

    /** @var string represents the success admin type */
    const TYPE_SUCCESS = 'success';

    /** @var string represents the warning admin type */
    const TYPE_WARNING = 'warning';

    /** @var string the admin notice ID */
    protected $id;

    /** @var bool determines whether the notice is dismissible or not, defaults to true */
    protected $dismissible = true;

    /** @var string the notice's content */
    protected $content;

    /** @var string the notice's title */
    protected $title;

    /** @var string type the notice's type, defaults to info */
    protected $type = self::TYPE_INFO;

    /** @var array the list of restricted user capabilities that can see this notice */
    protected $restrictedUserCapabilities = [];

    /** @var callable a condition to be evaluated before rendering the notice */
    protected $renderCondition;

    /**
     * Gets the notice ID.
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id ?? '';
    }

    /**
     * Gets the dismissible flag.
     *
     * @return bool
     */
    public function getDismissible() : bool
    {
        return $this->dismissible;
    }

    /**
     * Gets the notice's content.
     *
     * @return string
     */
    public function getContent() : ?string
    {
        return $this->content;
    }

    /**
     * Gets the notice title.
     *
     * @return string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * Gets the notice type.
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Gets the allowed user capabilities.
     *
     * @return array
     */
    public function getRestrictedUserCapabilities() : array
    {
        return $this->restrictedUserCapabilities;
    }

    /**
     * Gets the render condition.
     *
     * @return callable
     */
    public function getRenderCondition() : callable
    {
        return $this->renderCondition ?? function () {
            return true;
        };
    }

    /**
     * Sets the notice ID.
     *
     * @param string $value
     *
     * @return self
     */
    public function setId(string $value) : Notice
    {
        $this->id = $value;

        return $this;
    }

    /**
     * Sets the dismissible flag.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setDismissible(bool $value) : Notice
    {
        $this->dismissible = $value;

        return $this;
    }

    /**
     * Sets the notice content.
     *
     * @param string $value
     *
     * @return self
     */
    public function setContent(string $value) : Notice
    {
        $this->content = $value;

        return $this;
    }

    /**
     * Sets the notice title.
     *
     * @param string $value
     *
     * @return self
     */
    public function setTitle(string $value) : Notice
    {
        $this->title = $value;

        return $this;
    }

    /**
     * Sets the notice type.
     *
     * @param string $value
     *
     * @return self
     */
    public function setType(string $value) : Notice
    {
        $this->type = $value;

        return $this;
    }

    /**
     * Sets the allowed user capabilities.
     *
     * @param array $value
     *
     * @return self
     */
    public function setRestrictedUserCapabilities(array $value) : Notice
    {
        $this->restrictedUserCapabilities = $value;

        return $this;
    }

    /**
     * Sets the render condition.
     *
     * @param callable $renderCondition
     *
     * @return self
     */
    public function setRenderCondition(callable $renderCondition) : Notice
    {
        $this->renderCondition = $renderCondition;

        return $this;
    }

    /**
     * Gets the notice renderable HTML.
     *
     * @return string
     */
    public function getHtml() : string
    {
        $content = ! empty($this->getContent()) ? wpautop($this->getContent()) : '';

        $title = ! empty($this->getTitle()) ? wpautop('<strong>'.$this->getTitle().'</strong>') : '';

        ob_start(); ?>
        <div class="notice <?php echo esc_attr($this->getNoticeClass()); ?> <?php echo $this->getDismissible() ? 'is-dismissible' : ''; ?>" data-message-id="<?php echo esc_attr($this->getId()); ?>" id="<?php echo esc_attr($this->getId()); ?>">
            <?php echo $title; ?>
            <?php echo $content; ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Gets the notice class based on its type.
     *
     * @return string
     */
    protected function getNoticeClass() : string
    {
        $validTypes = [
            static::TYPE_ERROR,
            static::TYPE_INFO,
            static::TYPE_SUCCESS,
            static::TYPE_WARNING,
        ];

        return ArrayHelper::contains($validTypes, $this->getType()) ? 'notice-'.$this->getType() : '';
    }

    /**
     * Determines whether the current user can see the notice or not.
     *
     * An empty list of restricted user capabilities means that any user can see the notice.
     *
     * @return bool
     */
    public function currentUserCanSeeNotice() : bool
    {
        foreach ($this->restrictedUserCapabilities as $capability) {
            if (current_user_can($capability)) {
                return true;
            }
        }

        return empty($this->restrictedUserCapabilities);
    }

    /**
     * Determines whether the current notice is already dismissed or not.
     *
     * @return bool
     */
    public function isDismissed() : bool
    {
        $user = User::getCurrent();

        return null !== $user && Notices::isNoticeDismissed($user, $this->getId());
    }

    /**
     * Determines whether this notice should be displayed or not.
     *
     * @return bool
     */
    public function shouldDisplay() : bool
    {
        $renderConditionPasses = is_callable($this->getRenderCondition()) && call_user_func($this->getRenderCondition());

        return
            ! empty($this->getId()) &&
            $renderConditionPasses &&
            $this->currentUserCanSeeNotice() &&
            ! $this->isDismissed();
    }
}
