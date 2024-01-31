#!/bin/bash

# Get the input parameter
input_param=$1

# Replace "staging" with "stage" in the input parameter
output_param=${input_param/staging/stage}

# Print the output parameter
echo "$output_param"
