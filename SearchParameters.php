<?php

class SearchParameters {

  private $search_params;

  function __construct($search_params)
  {
    $this->search_params = $search_params;
  }

  public function getSearchParamData() {
    return $this->search_params;
  }

  public function getZips()
  {
    if (is_array($this->search_params['zip'])) {
      $zips = array_filter($this->search_params['zip']);
    } else {
      $zips = array();
    }

    return $zips;
  }

  public function getCities()
  {
    if (is_array($this->search_params['city'])) {
        $cities = array_filter($this->search_params['city']);
    } else {
        $cities = array();
    }
    return $cities;
  }

  public function getZoning()
  {
    if (is_array($this->search_params['zoning'])) {
        $zoning = array_filter($this->search_params['zoning']);
    } else {
        $zoning = array();
    }

    return $zoning;
  }

  public function getExemption()
  {
    if (is_array($this->search_params['exemption'])) {
        $exemption = array_filter($this->search_params['exemption']);
    } else {
        $exemption = array();
    }

    return $exemption;
  }


  public function getNumUnitsMin()
  {
    return $this->search_params['num_units_min'];
  }

  public function getNumUnitsMax()
  {
    return $this->search_params['num_units_max'];
  }

  public function getNumBedsMin()
  {
    return $this->search_params['num_baths_min'];
  }

  public function getNumBedsMax()
  {
    return $this->search_params['num_baths_max'];
  }

  public function getNumBathsMin()
  {
    return $this->search_params['num_baths_min'];
  }

  public function getNumBathsMax()
  {
    return $this->search_params['num_baths_max'];
  }

  public function getNumStoriesMin()
  {
    return $this->search_params['num_stories_min'];
  }

  public function getNumStoriesMax()
  {
    return $this->search_params['num_stories_max'];
  }

  public function getCostPerSqFeetMin()
  {
    return $this->search_params['cost_per_sq_ft_min'];
  }

  public function getCostPerSqFeetMax()
  {
    return $this->search_params['cost_per_sq_ft_max'];
  }

  public function getLotAreaSqFeetMin()
  {
    return $this->search_params['lot_area_sq_ft_min'];
  }

  public function getLotAreaSqFeetMax()
  {
    return $this->search_params['lot_area_sq_ft_max'];
  }

  public function getSalesPriceMin()
  {
    return $this->search_params['sales_price_min'];
  }

  public function getSalesPriceMax()
  {
    return $this->search_params['sales_price_max'];
  }

  public function isOwnerOccupied()
  {
    return $this->search_params['is_owner_occupied'];
  }

  public function isSingleFamilyOnly()
  {
    return $this->search_params['sfmlytype'];
  }

  public function getYearBuildMin()
  {
    return $this->search_params['year_built_min'];
  }

  public function getYearBuildMax()
  {
    return $this->search_params['year_built_max'];
  }

  public function getSalesDateMin()
  {
    return $this->search_params['sales_date_from'];
  }

  public function getSalesDateMax()
  {
    return $this->search_params['sales_date_to'];
  }

  public function getCaseTypeFilters()
  {
    if (is_array($this->search_params['casetype'])) {
      $case_type = array_filter($this->search_params['casetype']);
    } else {
      $case_type = array();
    }

    return $case_type;
  }

}
