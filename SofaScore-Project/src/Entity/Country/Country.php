<?php


namespace App\Entity\Country;

use Doctrine\ORM\Mapping as ORM;
use League\ISO3166;

/**
 * Class Country
 * @ORM\Embeddable()
 * @package App\Entity\Country
 */
class Country
{

    /**
     * @ORM\Column(type="string", nullable=true, length=2)
     * @var string|null
     */
    protected ?string $isoAlpha2;

    public function __construct(?string $isoAlpha2 = null)
    {
        $this->isoAlpha2 = $isoAlpha2;
    }

    /**
     * @return string|null
     */
    public function getIsoAlpha2(): ?string
    {
        return $this->isoAlpha2;
    }

    /**
     * @param string|null $isoAlpha2
     */
    public function setIsoAlpha2(?string $isoAlpha2): void
    {
        $this->isoAlpha2 = $isoAlpha2;
    }

    public function getName(): ?string
    {
        /*
         * reference:
         * https://github.com/thephpleague/iso3166#user-content-using
         */

        if(is_null($this->isoAlpha2)){
            return null;
        }

        $country = (new ISO3166\ISO3166)->alpha2($this->getIsoAlpha2());
        return $country["name"];
    }

}
