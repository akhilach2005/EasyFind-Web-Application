import os
import sys
import traceback
from pathlib import Path

# First, let's verify the libraries are installed
try:
    import cv2
    import numpy as np
except ImportError as e:
    print(f"ERROR: Required library not installed: {e}")
    print("Please install required libraries with: pip install opencv-python numpy")
    sys.exit(1)

def load_images_from_folder(folder):
    """Load all images from a folder and return a dictionary of image names and their data."""
    images = {}
    for filename in os.listdir(folder):
        img_path = os.path.join(folder, filename)
        if os.path.isfile(img_path):
            try:
                img = cv2.imread(img_path)
                if img is not None:
                    images[filename] = img
            except Exception as e:
                print(f"Error loading {filename}: {e}")
    return images

def extract_features(image):
    """Extract SIFT features from an image."""
    try:
        # Convert image to grayscale
        if len(image.shape) == 3:
            gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        else:
            gray = image
        
        # Create SIFT detector
        sift = cv2.SIFT_create()
        
        # Detect keypoints and compute descriptors
        keypoints, descriptors = sift.detectAndCompute(gray, None)
        
        return keypoints, descriptors
    except Exception as e:
        print(f"Error extracting features: {e}")
        return None, None

def match_images(lost_image, found_images, threshold=0.7):
    """Match a lost image with all found images and return matches above threshold."""
    matches = {}
    
    # Extract features from lost image
    lost_keypoints, lost_descriptors = extract_features(lost_image)
    
    # If no features found in lost image
    if lost_descriptors is None or len(lost_descriptors) == 0:
        print("Warning: No features found in the lost item image")
        return matches
    
    for found_name, found_image in found_images.items():
        try:
            # Extract features from found image
            found_keypoints, found_descriptors = extract_features(found_image)
            
            # If no features found in found image
            if found_descriptors is None or len(found_descriptors) == 0:
                continue
            
            # Use FLANN for matching
            FLANN_INDEX_KDTREE = 1
            index_params = dict(algorithm=FLANN_INDEX_KDTREE, trees=5)
            search_params = dict(checks=50)
            flann = cv2.FlannBasedMatcher(index_params, search_params)
            
            # Find matches
            matches_result = flann.knnMatch(lost_descriptors, found_descriptors, k=2)
            
            # Apply ratio test to find good matches
            good_matches = []
            for match_pair in matches_result:
                if len(match_pair) != 2:
                    continue
                m, n = match_pair
                if m.distance < threshold * n.distance:
                    good_matches.append(m)
            
            # Store match score (percentage of good matches relative to total keypoints)
            match_score = len(good_matches) / max(len(lost_keypoints), 1)
            matches[found_name] = match_score
        except Exception as e:
            print(f"Error matching with {found_name}: {e}")
    
    return matches

def process_single_item(lost_item_filename):
    """Process a single lost item against all found items."""
    try:
        script_dir = Path(__file__).parent
        lost_folder = script_dir / "Uploads"
        found_folder = script_dir / "FoundOnes"
        
        # Debug info
        print(f"Script directory: {script_dir}")
        print(f"Lost items folder: {lost_folder}")
        print(f"Found items folder: {found_folder}")
        
        # Check if folders exist
        if not lost_folder.exists():
            print(f"Error: Lost items folder not found at {lost_folder}")
            return False
            
        if not found_folder.exists():
            print(f"Error: Found items folder not found at {found_folder}")
            return False
        
        # Load the specific lost image
        lost_image_path = lost_folder / lost_item_filename
        print(f"Looking for lost image at: {lost_image_path}")
        
        if not lost_image_path.exists():
            print(f"Error: Lost item file '{lost_item_filename}' not found.")
            return False
        
        lost_image = cv2.imread(str(lost_image_path))
        if lost_image is None:
            print(f"Error: Could not load image '{lost_item_filename}'.")
            return False
        
        # Load all found images
        found_images = load_images_from_folder(found_folder)
        print(f"Checking '{lost_item_filename}' against {len(found_images)} found items.")
        
        if len(found_images) == 0:
            print(f"Warning: No found items to compare with in {found_folder}")
            return False
        
        # Match threshold - adjust as needed (higher = more strict matching)
        match_threshold = 0.7
        similarity_threshold = 0.1  # Minimum similarity score to consider a match
        
        # Match with all found items
        matches = match_images(lost_image, found_images, match_threshold)
        
        # Sort matches by score (highest first)
        sorted_matches = sorted(matches.items(), key=lambda x: x[1], reverse=True)
        
        if sorted_matches and sorted_matches[0][1] >= similarity_threshold:
            best_match = sorted_matches[0]
            match_percentage = best_match[1] * 100
            print(f"MATCH FOUND: Your item appears to be among the found items!")
            print(f"Best match: '{best_match[0]}' (Similarity: {match_percentage:.1f}%)")
            
            # Save the result to a file that can be accessed by the PHP script
            with open(script_dir / "last_match_result.txt", "w") as f:
                f.write(f"match\n{best_match[0]}\n{match_percentage:.1f}")
            
            return True
        else:
            print("NO MATCH: Your item does not appear to be among the found items.")
            
            # Save the result to a file that can be accessed by the PHP script
            with open(script_dir / "last_match_result.txt", "w") as f:
                f.write("no_match")
            
            return False
    except Exception as e:
        print(f"Error processing item: {e}")
        print("Traceback:")
        traceback.print_exc()
        return False

if __name__ == "__main__":
    # Check if filename was provided as argument
    if len(sys.argv) != 2:
        print("Usage: python imageMatcher.py <filename>")
        sys.exit(1)
    
    # Get filename from command line argument
    lost_item_filename = sys.argv[1]
    print(f"Processing lost item: {lost_item_filename}")
    
    # Process the single item
    result = process_single_item(lost_item_filename)
    
    if result:
        sys.exit(0)  # Success
    else:
        sys.exit(0)  # Still exit with success code to get output in PHP