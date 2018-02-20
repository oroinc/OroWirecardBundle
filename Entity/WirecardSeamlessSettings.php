<?php

namespace Oro\Bundle\WirecardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @ORM\Entity(repositoryClass="Oro\Bundle\WirecardBundle\Entity\Repository\WirecardSeamlessSettingsRepository")
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WirecardSeamlessSettings extends Transport
{
    const CREDIT_CARD_LABELS_KEY = 'credit_card_labels';
    const CREDIT_CARD_SHORT_LABELS_KEY = 'credit_card_short_labels';
    const PAYPAL_LABELS_KEY = 'paypal_labels';
    const PAYPAL_SHORT_LABELS_KEY = 'paypal_short_labels';
    const SEPA_LABELS_KEY = 'sepa_labels';
    const SEPA_SHORT_LABELS_KEY = 'sepa_short_labels';

    const CUSTOMER_ID_KEY = 'customer_id';
    const SHOP_ID_KEY = 'shop_id';
    const SECRET_KEY = 'secret';
    const TEST_MODE_KEY = 'test_mode';

    /**
     * @var ParameterBag
     */
    protected $settings;

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="oro_wcs_credit_card_lbl",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    protected $creditCardLabels;

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="oro_wcs_credit_card_sh_lbl",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    protected $creditCardShortLabels;

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="oro_wcs_paypal_lbl",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    protected $paypalLabels;

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="oro_wcs_paypal_sh_lbl",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    protected $paypalShortLabels;

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="oro_wcs_sepa_lbl",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    protected $sepaLabels;

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="oro_wcs_sepa_sh_lbl",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    protected $sepaShortLabels;

    /**
     * @var string
     *
     * @ORM\Column(name="oro_wcs_customer_id", type="string", length=255, nullable=false)
     */
    protected $customerId;

    /**
     * @var string
     *
     * @ORM\Column(name="oro_wcs_shop_id", type="string", length=255, nullable=false)
     */
    protected $shopId;

    /**
     * @var string
     *
     * @ORM\Column(name="oro_wcs_secret", type="string", length=255, nullable=false)
     */
    protected $secret;

    /**
     * @var boolean
     *
     * @ORM\Column(name="oro_wcs_test_mode", type="boolean", options={"default"=false})
     */
    protected $wcTestMode = false;

    public function __construct()
    {
        $this->creditCardLabels = new ArrayCollection();
        $this->creditCardShortLabels = new ArrayCollection();
        $this->paypalLabels = new ArrayCollection();
        $this->paypalShortLabels = new ArrayCollection();
        $this->sepaLabels = new ArrayCollection();
        $this->sepaShortLabels = new ArrayCollection();
    }

    /**
     * @return ParameterBag
     */
    public function getSettingsBag()
    {
        if (null === $this->settings) {
            $this->settings = new ParameterBag(
                [
                    self::CUSTOMER_ID_KEY => $this->getCustomerId(),
                    self::SHOP_ID_KEY => $this->getShopId(),
                    self::SECRET_KEY => $this->getSecret(),
                    self::CREDIT_CARD_LABELS_KEY => $this->getCreditCardLabels(),
                    self::CREDIT_CARD_SHORT_LABELS_KEY => $this->getCreditCardShortLabels(),
                    self::PAYPAL_LABELS_KEY => $this->getPayPalLabels(),
                    self::PAYPAL_SHORT_LABELS_KEY => $this->getPayPalShortLabels(),
                    self::SEPA_LABELS_KEY => $this->getSepaLabels(),
                    self::SEPA_SHORT_LABELS_KEY => $this->getSepaShortLabels(),
                    self::TEST_MODE_KEY => $this->isWcTestMode(),
                ]
            );
        }

        return $this->settings;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return (string)$this->customerId;
    }

    /**
     * @param string $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return (string)$this->shopId;
    }

    /**
     * @param string $shopId
     *
     * @return $this
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return (string)$this->secret;
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Add creditCardLabel.
     *
     * @param LocalizedFallbackValue $creditCardLabel
     *
     * @return $this
     */
    public function addCreditCardLabel(LocalizedFallbackValue $creditCardLabel)
    {
        return $this->addLabel($this->creditCardLabels, $creditCardLabel);
    }

    /**
     * Remove creditCardLabel.
     *
     * @param LocalizedFallbackValue $creditCardLabel
     *
     * @return $this
     */
    public function removeCreditCardLabel(LocalizedFallbackValue $creditCardLabel)
    {
        return $this->removeLabel($this->creditCardLabels, $creditCardLabel);
    }

    /**
     * Get creditCardLabels.
     *
     * @return Collection
     */
    public function getCreditCardLabels()
    {
        return $this->creditCardLabels;
    }

    /**
     * Add creditCardShortLabel.
     *
     * @param LocalizedFallbackValue $creditCardShortLabel
     *
     * @return $this
     */
    public function addCreditCardShortLabel(LocalizedFallbackValue $creditCardShortLabel)
    {
        return $this->addLabel($this->creditCardShortLabels, $creditCardShortLabel);
    }

    /**
     * Remove creditCardShortLabel.
     *
     * @param LocalizedFallbackValue $creditCardShortLabel
     *
     * @return $this
     */
    public function removeCreditCardShortLabel(LocalizedFallbackValue $creditCardShortLabel)
    {
        return $this->removeLabel($this->creditCardShortLabels, $creditCardShortLabel);
    }

    /**
     * Get creditCardShortLabels.
     *
     * @return Collection
     */
    public function getCreditCardShortLabels()
    {
        return $this->creditCardShortLabels;
    }

    /**
     * Add PayPalLabel.
     *
     * @param LocalizedFallbackValue $paypalLabel
     *
     * @return $this
     */
    public function addPayPalLabel(LocalizedFallbackValue $paypalLabel)
    {
        return $this->addLabel($this->paypalLabels, $paypalLabel);
    }

    /**
     * Remove PayPalLabel.
     *
     * @param LocalizedFallbackValue $paypalLabel
     *
     * @return $this
     */
    public function removePayPalLabel(LocalizedFallbackValue $paypalLabel)
    {
        return $this->removeLabel($this->paypalLabels, $paypalLabel);
    }

    /**
     * Get PayPalLabels.
     *
     * @return Collection
     */
    public function getPayPalLabels()
    {
        return $this->paypalLabels;
    }

    /**
     * Add PayPalShortLabel.
     *
     * @param LocalizedFallbackValue $paypalShortLabel
     *
     * @return $this
     */
    public function addPayPalShortLabel(LocalizedFallbackValue $paypalShortLabel)
    {
        return $this->addLabel($this->paypalShortLabels, $paypalShortLabel);
    }

    /**
     * Remove PayPalShortLabel.
     *
     * @param LocalizedFallbackValue $paypalShortLabel
     *
     * @return $this
     */
    public function removePayPalShortLabel(LocalizedFallbackValue $paypalShortLabel)
    {
        return $this->removeLabel($this->paypalShortLabels, $paypalShortLabel);
    }

    /**
     * Get PayPalShortLabels.
     *
     * @return Collection
     */
    public function getPayPalShortLabels()
    {
        return $this->paypalShortLabels;
    }

    /**
     * Add SepaLabel.
     *
     * @param LocalizedFallbackValue $sepaLabel
     *
     * @return $this
     */
    public function addSepaLabel(LocalizedFallbackValue $sepaLabel)
    {
        return $this->addLabel($this->sepaLabels, $sepaLabel);
    }

    /**
     * Remove SepaLabel.
     *
     * @param LocalizedFallbackValue $sepaLabel
     *
     * @return $this
     */
    public function removeSepaLabel(LocalizedFallbackValue $sepaLabel)
    {
        return $this->removeLabel($this->sepaLabels, $sepaLabel);
    }

    /**
     * Get SepaLabels.
     *
     * @return Collection
     */
    public function getSepaLabels()
    {
        return $this->sepaLabels;
    }

    /**
     * Add SepaShortLabel.
     *
     * @param LocalizedFallbackValue $sepaShortLabel
     *
     * @return $this
     */
    public function addSepaShortLabel(LocalizedFallbackValue $sepaShortLabel)
    {
        return $this->addLabel($this->sepaShortLabels, $sepaShortLabel);
    }

    /**
     * Remove SepaShortLabel.
     *
     * @param LocalizedFallbackValue $sepaShortLabel
     *
     * @return $this
     */
    public function removeSepaShortLabel(LocalizedFallbackValue $sepaShortLabel)
    {
        return $this->removeLabel($this->sepaShortLabels, $sepaShortLabel);
    }

    /**
     * Get SepaShortLabels.
     *
     * @return Collection
     */
    public function getSepaShortLabels()
    {
        return $this->sepaShortLabels;
    }

    /**
     * @param Collection $collection
     * @param LocalizedFallbackValue $label
     *
     * @return $this
     */
    private function addLabel(Collection $collection, LocalizedFallbackValue $label)
    {
        if (!$collection->contains($label)) {
            $collection->add($label);
        }

        return $this;
    }

    /**
     * @param Collection $collection
     * @param LocalizedFallbackValue $label
     *
     * @return $this
     */
    private function removeLabel(Collection $collection, LocalizedFallbackValue $label)
    {
        if ($collection->contains($label)) {
            $collection->removeElement($label);
        }

        return $this;
    }

    /**
     * Set wcTestMode.
     *
     * @param boolean $wcTestMode
     *
     * @return $this
     */
    public function setWcTestMode($wcTestMode)
    {
        $this->wcTestMode = $wcTestMode;

        return $this;
    }

    /**
     * Get wcTestMode.
     *
     * @return boolean
     */
    public function isWcTestMode()
    {
        return (bool)$this->wcTestMode;
    }
}
