## Approach

- My approach to this problem was to use a greedy algorithm that works in the following way:
    - We have the ability to generate all of the possible scores for a driver without having to look at any address data, in our case
      that's 4 possible scores for each driver:
        - Even address length, no shared factors
        - Even address length, with shared factors
        - Odd address length, with shared factors
        - Odd address length, no shared factors
- With this knowledge, we can generate the possible scores ahead of time and insert each possible suitability score into a priority queue
    - By using the suitability score as the priority value, we can iterate through the possible scores in decreasing order, ensuring
      that we lock in the highest scores possible.

## Downsides to this approach

- I don't believe this algorithm guarantees the highest possible total suitability score, though it should consistently perform
  very well in the regard.


    