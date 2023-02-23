### Implementation
- Notes detailing the algorihtm used to solve the problem can be found [here](src/Shipment/ShipmentMatcher/README.md)

### Assumptions

- Regarding shipment destination data:
    - Since only the street name of the shipment destination is relevant in the calculation of the score I omitted
      all other info (street number, city, region, postal code, etc.)
        - I chose this route because I don't believe it was the intent of the exercise to work out the details of extracting
          those individual address fields from a string as that can be a fairly complicated problem, especially if you want to account
          for international addresses.
