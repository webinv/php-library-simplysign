<?php
/**
 * SimplySign Xades Atom Client
 */

namespace Webinv\SimplySign\Model\Xades\Signing;

/**
 * Class Extra
 *
 * @package Webinv\SimplySign\Model\Xades\Signing
 * @author <li-on@wp.pl>
 */
class Extra implements \JsonSerializable
{
    /**
     * @var array $commitmentTypeIndication array with keys: "ProofOfApproval", "ProofOfOrigin", "ProofOfReceipt", "ProofOfDelivery", "ProofOfSender", "ProofOfCreation"
     */
    private $commitmentTypeIndication;

    /**
     * @var array
     */
    private $claimedRoles;

    /**
     * @var string
     */
    private $productionPlaceCity;
    
    /**
     * @var string
     */
    private $productionPlaceStateOrProvince;
    
    /**
     * @var string
     */
    private $productionPlacePostalCode;
    
    /**
     * @var string
     */
    private $productionPlaceCountryName;

    public function getCommitmentTypeIndication(){
		return $this->commitmentTypeIndication;
	}

	public function setCommitmentTypeIndication($commitmentTypeIndication){
		$this->commitmentTypeIndication = $commitmentTypeIndication;
        return $this;
	}

	public function getClaimedRoles(){
		return $this->claimedRoles;
	}

	public function setClaimedRoles($claimedRoles){
		$this->claimedRoles = $claimedRoles;
        return $this;
	}

	public function getProductionPlaceCity(){
		return $this->productionPlaceCity;
	}

	public function setProductionPlaceCity($productionPlaceCity){
		$this->productionPlaceCity = $productionPlaceCity;
        return $this;
	}

	public function getProductionPlaceStateOrProvince(){
		return $this->productionPlaceStateOrProvince;
	}

	public function setProductionPlaceStateOrProvince($productionPlaceStateOrProvince){
		$this->productionPlaceStateOrProvince = $productionPlaceStateOrProvince;
        return $this;
	}

	public function getProductionPlacePostalCode(){
		return $this->productionPlacePostalCode;
	}

	public function setProductionPlacePostalCode($productionPlacePostalCode){
		$this->productionPlacePostalCode = $productionPlacePostalCode;
        return $this;
	}

	public function getProductionPlaceCountryName(){
		return $this->productionPlaceCountryName;
	}

	public function setProductionPlaceCountryName($productionPlaceCountryName){
		$this->productionPlaceCountryName = $productionPlaceCountryName;
        return $this;
	}
    
    /**
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = [];

        if (null !== $this->commitmentTypeIndication) {
            $data['commitmentTypeIndication'] = $this->commitmentTypeIndication;
        }

        if (null !== $this->claimedRoles) {
            $data['signerRoles'] = [
                    'claimedRoles' => $this->claimedRoles;
                ]
        }

        if (null !== $this->productionPlaceCity) {
            $data['productionPlace'] = [];
            $data['productionPlace']['city'] = $this->productionPlaceCity;
            if (null !== $this->productionPlaceStateOrProvince) {
                $data['productionPlace']['stateOrProvince'] = $this->productionPlaceStateOrProvince;
            }
            if (null !== $this->productionPlacePostalCode) {
                $data['productionPlace']['postalCode'] = $this->productionPlacePostalCode;
            }
            if (null !== $this->productionPlaceCountryName) {
                $data['productionPlace']['countryName'] = $this->productionPlaceCountryName;
            }
        }

        return $data;
    }
}
