import logging
import sys
from pathlib import Path
import numpy as np
import pandas as pd

# Setup Logging
logging.basicConfig(
    level=logging.INFO,
    format="[%(asctime)s] [%(levelname)s] [%(name)s]: %(message)s",
    datefmt="%Y-%m-%d %H:%M:%S",
)
logger = logging.getLogger("genomics_parser")

# File Paths
RAW_TSV_PATH = Path("data/raw/tcga_gbm_raw.tsv")
PROCESSED_CSV_PATH = Path("data/processed/tcga_gbm_normalized.csv")


def load_raw_tsv(file_path: Path) -> pd.DataFrame:
    """Load tab-separated expression matrix."""
    if not file_path.exists():
        logger.error(f"Input file not found at {file_path}")
        sys.exit(1)

    logger.info(f"Loading raw expression matrix from {file_path}...")
    try:
        # Load TSV assuming index column is the first column (e.g., Gene symbols/IDs)
        df = pd.read_csv(file_path, sep="\t", index_col=0)
        logger.info(f"Loaded matrix shape: {df.shape} (Genes x Samples)")
        return df
    except Exception as e:
        logger.error(f"Failed to read TSV file: {e}")
        sys.exit(1)


def normalize_log2(matrix: pd.DataFrame) -> pd.DataFrame:
    """Apply log2(x + 1) normalization to expression matrix."""
    logger.info("Applying log2(x + 1) normalization...")
    matrix_float = matrix.astype(float)
    return np.log2(matrix_float + 1.0)


def export_processed_data(matrix: pd.DataFrame, output_path: Path):
    """Save normalized matrix to CSV for downstream application use."""
    output_path.parent.mkdir(parents=True, exist_ok=True)
    logger.info(f"Exporting processed matrix to {output_path}...")
    matrix.to_csv(output_path)
    logger.info("Export complete.")


def main():
    logger.info("Starting Glioblastoma Genomics Pipeline...")

    # 1. Read TSV
    raw_matrix = load_raw_tsv(RAW_TSV_PATH)

    # 2. Apply log2(x + 1) transformation
    normalized_matrix = normalize_log2(raw_matrix)

    # 3. Export normalized results
    export_processed_data(normalized_matrix, PROCESSED_CSV_PATH)

    summary = {
        "genes_processed": normalized_matrix.shape[0],
        "samples_processed": normalized_matrix.shape[1],
        "output_location": str(PROCESSED_CSV_PATH),
    }

    logger.info(f"Pipeline finished successfully. Summary: {summary}")


if __name__ == "__main__":
    main()
