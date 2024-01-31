#!/bin/bash

# Get the input parameter
input_param=$1

# Split the input parameter by comma
IFS=',' read -ra ADDR <<< "$input_param"

# Initialize an empty array to hold the formatted values
formatted_values=()

# Loop over each value and format it
for i in "${ADDR[@]}"; do
    # Format the value
    formatted_value="${i}"

    # Add the formatted value to the array
    formatted_values+=("$formatted_value")
done

# Join the formatted values with "and"
result=$(printf " and %s" "${formatted_values[@]}")
result=${result:5}

# Print the result
echo "$result"
