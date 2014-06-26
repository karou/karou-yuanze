<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Poslist
 */
class Poslist
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $store_id;

    /**
     * @var string
     */
    private $location_name;

    /**
     * @var integer
     */
    private $number_of_visits;

    /**
     * @var string
     */
    private $store_name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $address_2;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $county;

    /**
     * @var string
     */
    private $state_region;

    /**
     * @var string
     */
    private $postal_code;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $location_hours;

    /**
     * @var string
     */
    private $export_email;

    /**
     * @var string
     */
    private $export_email_name;

    /**
     * @var string
     */
    private $export_language;

    /**
     * @var string
     */
    private $location_status;

    /**
     * @var string
     */
    private $location_photo_url;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    /**
     * @var string
     */
    private $date_geocode_acquired;

    /**
     * @var string
     */
    private $geocode_source;

    /**
     * @var string
     */
    private $additional_comments;

    /**
     * @var string
     */
    private $summary_label;

    /**
     * @var string
     */
    private $summary_content;

    /**
     * @var string
     */
    private $summary_display;

    /**
     * @var string
     */
    private $region;

    /**
     * @var \Albatross\CustomBundle\Entity\Customwave
     */
    private $customwave;


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
     * Set store_id
     *
     * @param string $storeId
     * @return Poslist
     */
    public function setStoreId($storeId)
    {
        $this->store_id = $storeId;

        return $this;
    }

    /**
     * Get store_id
     *
     * @return string 
     */
    public function getStoreId()
    {
        return $this->store_id;
    }

    /**
     * Set location_name
     *
     * @param string $locationName
     * @return Poslist
     */
    public function setLocationName($locationName)
    {
        $this->location_name = $locationName;

        return $this;
    }

    /**
     * Get location_name
     *
     * @return string 
     */
    public function getLocationName()
    {
        return $this->location_name;
    }

    /**
     * Set number_of_visits
     *
     * @param integer $numberOfVisits
     * @return Poslist
     */
    public function setNumberOfVisits($numberOfVisits)
    {
        $this->number_of_visits = $numberOfVisits;

        return $this;
    }

    /**
     * Get number_of_visits
     *
     * @return integer 
     */
    public function getNumberOfVisits()
    {
        return $this->number_of_visits;
    }

    /**
     * Set store_name
     *
     * @param string $storeName
     * @return Poslist
     */
    public function setStoreName($storeName)
    {
        $this->store_name = $storeName;

        return $this;
    }

    /**
     * Get store_name
     *
     * @return string 
     */
    public function getStoreName()
    {
        return $this->store_name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Poslist
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
     * Set address_2
     *
     * @param string $address2
     * @return Poslist
     */
    public function setAddress2($address2)
    {
        $this->address_2 = $address2;

        return $this;
    }

    /**
     * Get address_2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address_2;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Poslist
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
     * @return Poslist
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
     * Set state_region
     *
     * @param string $stateRegion
     * @return Poslist
     */
    public function setStateRegion($stateRegion)
    {
        $this->state_region = $stateRegion;

        return $this;
    }

    /**
     * Get state_region
     *
     * @return string 
     */
    public function getStateRegion()
    {
        return $this->state_region;
    }

    /**
     * Set postal_code
     *
     * @param string $postalCode
     * @return Poslist
     */
    public function setPostalCode($postalCode)
    {
        $this->postal_code = $postalCode;

        return $this;
    }

    /**
     * Get postal_code
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Poslist
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Poslist
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
     * @return Poslist
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
     * Set location_hours
     *
     * @param string $locationHours
     * @return Poslist
     */
    public function setLocationHours($locationHours)
    {
        $this->location_hours = $locationHours;

        return $this;
    }

    /**
     * Get location_hours
     *
     * @return string 
     */
    public function getLocationHours()
    {
        return $this->location_hours;
    }

    /**
     * Set export_email
     *
     * @param string $exportEmail
     * @return Poslist
     */
    public function setExportEmail($exportEmail)
    {
        $this->export_email = $exportEmail;

        return $this;
    }

    /**
     * Get export_email
     *
     * @return string 
     */
    public function getExportEmail()
    {
        return $this->export_email;
    }

    /**
     * Set export_email_name
     *
     * @param string $exportEmailName
     * @return Poslist
     */
    public function setExportEmailName($exportEmailName)
    {
        $this->export_email_name = $exportEmailName;

        return $this;
    }

    /**
     * Get export_email_name
     *
     * @return string 
     */
    public function getExportEmailName()
    {
        return $this->export_email_name;
    }

    /**
     * Set export_language
     *
     * @param string $exportLanguage
     * @return Poslist
     */
    public function setExportLanguage($exportLanguage)
    {
        $this->export_language = $exportLanguage;

        return $this;
    }

    /**
     * Get export_language
     *
     * @return string 
     */
    public function getExportLanguage()
    {
        return $this->export_language;
    }

    /**
     * Set location_status
     *
     * @param string $locationStatus
     * @return Poslist
     */
    public function setLocationStatus($locationStatus)
    {
        $this->location_status = $locationStatus;

        return $this;
    }

    /**
     * Get location_status
     *
     * @return string 
     */
    public function getLocationStatus()
    {
        return $this->location_status;
    }

    /**
     * Set location_photo_url
     *
     * @param string $locationPhotoUrl
     * @return Poslist
     */
    public function setLocationPhotoUrl($locationPhotoUrl)
    {
        $this->location_photo_url = $locationPhotoUrl;

        return $this;
    }

    /**
     * Get location_photo_url
     *
     * @return string 
     */
    public function getLocationPhotoUrl()
    {
        return $this->location_photo_url;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Poslist
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
     * @return Poslist
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
     * Set date_geocode_acquired
     *
     * @param string $dateGeocodeAcquired
     * @return Poslist
     */
    public function setDateGeocodeAcquired($dateGeocodeAcquired)
    {
        $this->date_geocode_acquired = $dateGeocodeAcquired;

        return $this;
    }

    /**
     * Get date_geocode_acquired
     *
     * @return string 
     */
    public function getDateGeocodeAcquired()
    {
        return $this->date_geocode_acquired;
    }

    /**
     * Set geocode_source
     *
     * @param string $geocodeSource
     * @return Poslist
     */
    public function setGeocodeSource($geocodeSource)
    {
        $this->geocode_source = $geocodeSource;

        return $this;
    }

    /**
     * Get geocode_source
     *
     * @return string 
     */
    public function getGeocodeSource()
    {
        return $this->geocode_source;
    }

    /**
     * Set additional_comments
     *
     * @param string $additionalComments
     * @return Poslist
     */
    public function setAdditionalComments($additionalComments)
    {
        $this->additional_comments = $additionalComments;

        return $this;
    }

    /**
     * Get additional_comments
     *
     * @return string 
     */
    public function getAdditionalComments()
    {
        return $this->additional_comments;
    }

    /**
     * Set summary_label
     *
     * @param string $summaryLabel
     * @return Poslist
     */
    public function setSummaryLabel($summaryLabel)
    {
        $this->summary_label = $summaryLabel;

        return $this;
    }

    /**
     * Get summary_label
     *
     * @return string
     */
    public function getSummaryLabel()
    {
        return $this->summary_label;
    }

    /**
     * Set summary_content
     *
     * @param string $summaryContent
     * @return Poslist
     */
    public function setSummaryContent($summaryContent)
    {
        $this->summary_content = $summaryContent;

        return $this;
    }

    /**
     * Get summary_content
     *
     * @return string 
     */
    public function getSummaryContent()
    {
        return $this->summary_content;
    }

    /**
     * Set summary_display
     *
     * @param string $summaryDisplay
     * @return Poslist
     */
    public function setSummaryDisplay($summaryDisplay)
    {
        $this->summary_display = $summaryDisplay;

        return $this;
    }

    /**
     * Get summary_display
     *
     * @return string 
     */
    public function getSummaryDisplay()
    {
        return $this->summary_display;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Poslist
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
     * Set customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Poslist
     */
    public function setCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }

    /**
     * Get customwave
     *
     * @return \Albatross\CustomBundle\Entity\Customwave 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }
    /**
     * @var string
     */
    private $path;


    /**
     * Set path
     *
     * @param string $path
     * @return Poslist
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    //file upload
    private $file;
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }
    public function getFile() {
        return $this->file;
    }
    public function upload() {
        if (null === $this->getFile()) {
            return;
        }
        $this->getFile()->move(
                $this->getUploadRootDir(), $this->getFile()->getClientOriginalName()
        );
        $this->path = $this->getFile()->getClientOriginalName();
    }
    protected function getUploadDir() {
        $date = date('ymd');
        return 'PosList/'.$date.'/'.$this->getCustomwave();
    }
    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }
    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $poslistdata;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->poslistdata = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add poslistdata
     *
     * @param \Albatross\CustomBundle\Entity\Poslistdata $poslistdata
     * @return Poslist
     */
    public function addPoslistdatum(\Albatross\CustomBundle\Entity\Poslistdata $poslistdata)
    {
        $this->poslistdata[] = $poslistdata;

        return $this;
    }

    /**
     * Remove poslistdata
     *
     * @param \Albatross\CustomBundle\Entity\Poslistdata $poslistdata
     */
    public function removePoslistdatum(\Albatross\CustomBundle\Entity\Poslistdata $poslistdata)
    {
        $this->poslistdata->removeElement($poslistdata);
    }

    /**
     * Get poslistdata
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPoslistdata()
    {
        return $this->poslistdata;
    }
    /**
     * @var string
     */
    private $submittime;


    /**
     * Set submittime
     *
     * @param string $submittime
     * @return Poslist
     */
    public function setSubmittime($submittime)
    {
        $this->submittime = $submittime;

        return $this;
    }

    /**
     * Get submittime
     *
     * @return string 
     */
    public function getSubmittime()
    {
        return $this->submittime;
    }
}
