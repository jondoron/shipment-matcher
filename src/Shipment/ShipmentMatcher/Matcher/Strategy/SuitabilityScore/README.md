## Approach

- My approach to this problem was to use a greedy algorithm that works in the following way:
    - We have the ability to generate all the possible scores for a driver without having to look at any shipment destination data, in our case
      that's 4 possible scores for each driver:
        - Even address length, no shared factors
        - Even address length, with shared factors
        - Odd address length, with shared factors
        - Odd address length, no shared factors
- With this knowledge, we can generate the possible scores ahead of time and insert each possible suitability score into a priority queue
    - By using the suitability score as the priority value, we can iterate through the possible scores in decreasing order, ensuring
      that we lock in the highest scores possible.
    - By keeping track of which drivers have been assigned, we can immediately proceed to the next item in the queue if that driver is no longer valid
    - By organizing the shipment destination data a bit we can also cut down the lookup time for valid addresses

## Downsides to this approach

- I don't believe this algorithm guarantees the highest possible total suitability score, though it should consistently perform
  very well in the regard.


    