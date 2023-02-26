## Shipment Matcher

### Assumptions
- Regarding shipment destination data:
    - Since only the street name of the shipment destination is relevant in the calculation of the score I omitted
      all other info (street number, city, region, postal code, etc.)
        - I chose this route because I don't believe it was the intent of the exercise to work out the details of extracting
          those individual address fields from a string as that can be a fairly complicated problem, especially if you want to account
          for international addresses.

### Implementation
- Notes detailing the algorithm used to solve the problem can be found [here](src/Shipment/ShipmentMatcher/Matcher/Strategy/SuitabilityScore/README.md)

### Design Decisions
- I had 3 main goals in the overall design of the project:
  - I know that our data science team is super innovative and always pushing the envelope, so I wanted to make sure 
to introduce the abstractions that would allow us to swap out matching strategies easily.
    - Using the Strategy design pattern allows us to use any implementation of [ShipmentMatcherStrategyInterface](src/Shipment/ShipmentMatcher/Matcher/Strategy/ShipmentMatcherStrategyInterface.php)
we want interchangeably.
  - I know that the data required to generate the matches wasn't always going to come from a file, so I wanted to make sure
to introduce the right abstractions in case we eventually want to load them from a database, cloud storage, etc.
    - The matching strategy accepts the [RepositoryRegistryInterface](src/Shipment/ShipmentMatcher/Repository/RepositoryRegistryInterface.php)
and is agnostic to the mechanism used to load the Drivers and ShipmentDestinations
  - It also occurred to me that in some implementations, the entirety of the "matching process" might not even occur in the same process
    - Perhaps our implementation can be parallelized and run on several workers or serverless functions
  - With this in mind, I decided to break the matching portion into 2 steps
    - Data loading: `loadData()` function in [ShipmentMatcherStrategyInterface](src/Shipment/ShipmentMatcher/Matcher/Strategy/ShipmentMatcherStrategyInterface.php)
    - Generating the matches: `getResults()` function in [ShipmentMatcherStrategyInterface](src/Shipment/ShipmentMatcher/Matcher/Strategy/ShipmentMatcherStrategyInterface.php)
  - In theory, nothing would prevent you from triggering the data load in one process, and then trigger the generation of matches in another

### Curiosity
- Since these excercises tend to take some time, I always try to bake some exploration into them and get hands-on with new things
- In this project, I decided to explore some newer PHP 8+ features.
  - [Enums in PHP](src/Shipment/ShipmentMatcher/Entities/ScoringMethodEnum.php)
  - [Constructor Promotion](src/Shipment/ShipmentMatcher/Matcher/ShipmentMatcher.php)

___ 
## Usage Instructions

### Pre-requisites:
1. Install Docker for your platform: https://docs.docker.com/get-docker/ and ensure the Docker daemon is running.
2. Install PHP dependencies:
```
docker run --rm --interactive --tty \
  -v $PWD:/app \
  composer:2.5 install
```

### How to run:
1. Note: we're going to run the application using the Composer docker image as it comes bundles with php 8.2
and it allows us to use a slightly more friendly syntax to run scripts (similiar to using `npm run`)
```
docker run -it --rm \
  -v "$PWD":/app -w /app \
  composer:2.5 shipment-matcher {{PATH TO SHIPMENT DESTINATIONS FILE}} {{PATH TO DRIVERS FILE}}
```
2. To run using the sample shipment destination and driver files provided:
```
docker run -it --rm \
-v "$PWD":/app -w /app \
composer:2.5 shipment-matcher shipment_destinations.txt drivers.txt
```