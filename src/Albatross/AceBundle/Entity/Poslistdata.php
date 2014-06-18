<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Poslistdata
 *
 * @ORM\Table(name="poslistdata")
 * @ORM\Entity
 */
class Poslistdata
{
    /**
     * @var string
     *
     * @ORM\Column(name="store_id", type="string", length=255, nullable=true)
     */
    private $storeId;

    /**
     * @var string
     *
     * @ORM\Column(name="location_name", type="string", length=255, nullable=true)
     */
    private $locationName;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_visits", type="bigint", nullable=true)
     */
    private $numberOfVisits;

    /**
     * @var string
     *
     * @ORM\Column(name="store_name", type="string", length=255, nullable=true)
     */
    private $storeName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="address_2", type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="county", type="string", length=255, nullable=true)
     */
    private $county;

    /**
     * @var string
     *
     * @ORM\Column(name="state_region", type="string", length=255, nullable=true)
     */
    private $stateRegion;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=255, nullable=true)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="countrys", type="string", length=255, nullable=true)
     */
    private $countrys;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="location_hours", type="string", length=255, nullable=true)
     */
    private $locationHours;

    /**
     * @var string
     *
     * @ORM\Column(name="export_email", type="string", length=255, nullable=true)
     */
    private $exportEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="export_email_name", type="string", length=255, nullable=true)
     */
    private $exportEmailName;

    /**
     * @var string
     *
     * @ORM\Column(name="export_language", type="string", length=255, nullable=true)
     */
    private $exportLanguage;

    /**
     * @var string
     *
     * @ORM\Column(name="location_status", type="string", length=255, nullable=true)
     */
    private $locationStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="location_photo_url", type="string", length=255, nullable=true)
     */
    private $locationPhotoUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="date_geocode_acquired", type="string", length=255, nullable=true)
     */
    private $dateGeocodeAcquired;

    /**
     * @var string
     *
     * @ORM\Column(name="geocode_source", type="string", length=255, nullable=true)
     */
    private $geocodeSource;

    /**
     * @var string
     *
     * @ORM\Column(name="additional_comments", type="string", length=255, nullable=true)
     */
    private $additionalComments;

    /**
     * @var string
     *
     * @ORM\Column(name="summary_label", type="string", length=255, nullable=true)
     */
    private $summaryLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="summary_content", type="string", length=255, nullable=true)
     */
    private $summaryContent;

    /**
     * @var string
     *
     * @ORM\Column(name="summary_display", type="string", length=255, nullable=true)
     */
    private $summaryDisplay;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    /**
     * @var \Albatross\AceBundle\Entity\Poslist
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Poslist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="poslist_id", referencedColumnName="id")
     * })
     */
    private $poslist;



    /**
     * Set storeId
     *
     * @param string $storeId
     * @return Poslistdata
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;

        return $this;
    }

    /**
     * Get storeId
     *
     * @return string 
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * Set locationName
     *
     * @param string $locationName
     * @return Poslistdata
     */
    public function setLocationName($locationName)
    {
        $this->locationName = $locationName;

        return $this;
    }

    /**
     * Get locationName
     *
     * @return string 
     */
    public function getLocationName()
    {
        return $this->locationName;
    }

    /**
     * Set numberOfVisits
     *
     * @param integer $numberOfVisits
     * @return Poslistdata
     */
    public function setNumberOfVisits($numberOfVisits)
    {
        $this->numberOfVisits = $numberOfVisits;

        return $this;
    }

    /**
     * Get numberOfVisits
     *
     * @return integer 
     */
    public function getNumberOfVisits()
    {
        return $this->numberOfVisits;
    }

    /**
     * Set storeName
     *
     * @param string $storeName
     * @return Poslistdata
     */
    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * Get storeName
     *
     * @return string 
     */
    public function getStoreName()
    {
        return $this->storeName;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Poslistdata
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return Poslistdata
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Poslistdata
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set county
     *
     * @param string $county
     * @return Poslistdata
     */
    public function setCounty($county)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Get county
     *
     * @return string 
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * Set stateRegion
     *
     * @param string $stateRegion
     * @return Poslistdata
     */
    public function setStateRegion($stateRegion)
    {
        $this->stateRegion = $stateRegion;

        return $this;
    }

    /**
     * Get stateRegion
     *
     * @return string 
     */
    public function getStateRegion()
    {
        return $this->stateRegion;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return Poslistdata
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set countrys
     *
     * @param string $countrys
     * @return Poslistdata
     */
    public function setCountrys($countrys)
    {
        $this->countrys = $countrys;

        return $this;
    }

    /**
     * Get countrys
     *
     * @return string 
     */
    public function getCountrys()
    {
        return $this->countrys;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Poslistdata
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Poslistdata
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set locationHours
     *
     * @param string $locationHours
     * @return Poslistdata
     */
    public function setLocationHours($locationHours)
    {
        $this->locationHours = $locationHours;

        return $this;
    }

    /**
     * Get locationHours
     *
     * @return string 
     */
    public function getLocationHours()
    {
        return $this->locationHours;
    }

    /**
     * Set exportEmail
     *
     * @param string $exportEmail
     * @return Poslistdata
     */
    public function setExportEmail($exportEmail)
    {
        $this->exportEmail = $exportEmail;

        return $this;
    }

    /**
     * Get exportEmail
     *
     * @return string 
     */
    public function getExportEmail()
    {
        return $this->exportEmail;
    }

    /**
     * Set exportEmailName
     *
     * @param string $exportEmailName
     * @return Poslistdata
     */
    public function setExportEmailName($exportEmailName)
    {
        $this->exportEmailName = $exportEmailName;

        return $this;
    }

    /**
     * Get exportEmailName
     *
     * @return string 
     */
    public function getExportEmailName()
    {
        return $this->exportEmailName;
    }

    /**
     * Set exportLanguage
     *
     * @param string $exportLanguage
     * @return Poslistdata
     */
    public function setExportLanguage($exportLanguage)
    {
        $this->exportLanguage = $exportLanguage;

        return $this;
    }

    /**
     * Get exportLanguage
     *
     * @return string 
     */
    public function getExportLanguage()
    {
        return $this->exportLanguage;
    }

    /**
     * Set locationStatus
     *
     * @param string $locationStatus
     * @return Poslistdata
     */
    public function setLocationStatus($locationStatus)
    {
        $this->locationStatus = $locationStatus;

        return $this;
    }

    /**
     * Get locationStatus
     *
     * @return string 
     */
    public function getLocationStatus()
    {
        return $this->locationStatus;
    }

    /**
     * Set locationPhotoUrl
     *
     * @param string $locationPhotoUrl
     * @return Poslistdata
     */
    public function setLocationPhotoUrl($locationPhotoUrl)
    {
        $this->locationPhotoUrl = $locationPhotoUrl;

        return $this;
    }

    /**
     * Get locationPhotoUrl
     *
     * @return string 
     */
    public function getLocationPhotoUrl()
    {
        return $this->locationPhotoUrl;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Poslistdata
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Poslistdata
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set dateGeocodeAcquired
     *
     * @param string $dateGeocodeAcquired
     * @return Poslistdata
     */
    public function setDateGeocodeAcquired($dateGeocodeAcquired)
    {
        $this->dateGeocodeAcquired = $dateGeocodeAcquired;

        return $this;
    }

    /**
     * Get dateGeocodeAcquired
     *
     * @return string 
     */
    public function getDateGeocodeAcquired()
    {
        return $this->dateGeocodeAcquired;
    }

    /**
     * Set geocodeSource
     *
     * @param string $geocodeSource
     * @return Poslistdata
     */
    public function setGeocodeSource($geocodeSource)
    {
        $this->geocodeSource = $geocodeSource;

        return $this;
    }

    /**
     * Get geocodeSource
     *
     * @return string 
     */
    public function getGeocodeSource()
    {
        return $this->geocodeSource;
    }

    /**
     * Set additionalComments
     *
     * @param string $additionalComments
     * @return Poslistdata
     */
    public function setAdditionalComments($additionalComments)
    {
        $this->additionalComments = $additionalComments;

        return $this;
    }

    /**
     * Get additionalComments
     *
     * @return string 
     */
    public function getAdditionalComments()
    {
        return $this->additionalComments;
    }

    /**
     * Set summaryLabel
     *
     * @param string $summaryLabel
     * @return Poslistdata
     */
    public function setSummaryLabel($summaryLabel)
    {
        $this->summaryLabel = $summaryLabel;

        return $this;
    }

    /**
     * Get summaryLabel
     *
     * @return string 
     */
    public function getSummaryLabel()
    {
        return $this->summaryLabel;
    }

    /**
     * Set summaryContent
     *
     * @param string $summaryContent
     * @return Poslistdata
     */
    public function setSummaryContent($summaryContent)
    {
        $this->summaryContent = $summaryContent;

        return $this;
    }

    /**
     * Get summaryContent
     *
     * @return string 
     */
    public function getSummaryContent()
    {
        return $this->summaryContent;
    }

    /**
     * Set summaryDisplay
     *
     * @param string $summaryDisplay
     * @return Poslistdata
     */
    public function setSummaryDisplay($summaryDisplay)
    {
        $this->summaryDisplay = $summaryDisplay;

        return $this;
    }

    /**
     * Get summaryDisplay
     *
     * @return string 
     */
    public function getSummaryDisplay()
    {
        return $this->summaryDisplay;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Poslistdata
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return Poslistdata
     */
    public function setCountry(\Albatross\AceBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Albatross\AceBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set poslist
     *
     * @param \Albatross\AceBundle\Entity\Poslist $poslist
     * @return Poslistdata
     */
    public function setPoslist(\Albatross\AceBundle\Entity\Poslist $poslist = null)
    {
        $this->poslist = $poslist;

        return $this;
    }

    /**
     * Get poslist
     *
     * @return \Albatross\AceBundle\Entity\Poslist 
     */
    public function getPoslist()
    {
        return $this->poslist;
    }
}
