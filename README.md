### Assumptions

- Regarding shipment destination data:
    - Since only the street name of the shipment destination is relevant in the calculation of the score I omitted
      all other info (street number, city, region, postal code, etc.)
        - I chose this route because I don't believe it was the intent of the exercise to work out the details of extracting
          those individual address fields from a string as that can be a fairly complicated problem, especially if you want to account
          for international addresses.

### Implementation
- Notes detailing the algorihtm used to solve the problem can be found [here](src/Shipment/ShipmentMatcher/Matcher/Strategy/README.md)

### Design Decisions
- I had 3 main goals in the overall design of the project:
  - I know that our data science team is super innovative and always pushing the envelope, so I wanted to make sure 
to introduce the abstractions that would allow us to swap out matching strategies easily.
    - Using the Strategy design pattern allows us use any implementation of [ShipmentMatcherStrategyInterface](src/Shipment/ShipmentMatcher/Matcher/Strategy/ShipmentMatcherStrategyInterface.php)
we want interchangeably.
  - I know that the data required to generate the matches wasn't always going to come from a file, so I wanted to make sure
to introduce the right abstractions in case we eventually want to load them from a database, cloud storage, etc.
  - It also occurred to me that in some implementations, the entirety of the "matching process" might not even occur in the same process
    - Perhaps our implementation can be parallelized and run on several workers or serverless functions
  - With this in mind, I decided to break the matching portion into 2 steps
    - Data loading: `loadData()` function in [ShipmentMatcherStrategyInterface](src/Shipment/ShipmentMatcher/Matcher/Strategy/ShipmentMatcherStrategyInterface.php)
    - Generating the matches: `generateMatches()` function in [ShipmentMatcherStrategyInterface](src/Shipment/ShipmentMatcher/Matcher/Strategy/ShipmentMatcherStrategyInterface.php)
  - In theory, nothing would prevent you from triggering the data load in one process, and then trigger the generation of matches in another

### Curiosity
- Since these excercises tend to take some time, I always try to bake some exploration into them and get hands-on with new things
- In this project, I decided to explore some of the newer PHP 8+ features.
  - [Enums in PHP](src/Shipment/ShipmentMatcher/Models/ScoringMethodEnum.php)
  - [Constructor Promotion](src/Shipment/ShipmentMatcher/Matcher/ShipmentMatcher.php)
