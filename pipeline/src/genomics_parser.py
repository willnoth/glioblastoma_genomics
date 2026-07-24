import logging
from pathlib import Path
import numpy as np
import pandas as pd

logging.basicConfig(
    level=logging.INFO,
    format="[%(asctime)s] [%(levelname)s] [%(name)s]: %(message)s",
    datefmt="%Y-%m-%d %H:%M:%S",
)
logger = logging.getLogger("genomics_parser")


class GenomicsParser:
    def __init__(self, data_dir: str = "data"):
        self.data_dir = Path(data_dir)

    def load_matrix(self, filepath: str | Path) -> pd.DataFrame:
        """Loads a gene expression matrix (genes as rows, sample IDs as columns)."""
        path = Path(filepath)
        if not path.exists():
            raise FileNotFoundError(f"Matrix file not found at: {path}")

        logger.info(f"Loading expression matrix from {path.name}...")
        # Supports TSV or CSV based on extension
        sep = "\t" if path.suffix in [".tsv", ".txt"] else ","
        df = pd.read_csv(path, index_col=0, sep=sep)
        logger.info(f"Loaded matrix shape: {df.shape} (Genes x Samples)")
        return df

    def apply_log2_transform(self, df: pd.DataFrame) -> pd.DataFrame:
        """Applies log2(x + 1) normalization to gene expression values."""
        logger.info("Applying log2(x + 1) normalization...")
        # Ensure matrix contains numeric data and clip negative noise if present
        numeric_df = df.apply(pd.to_numeric, errors="coerce").fillna(0)
        numeric_df = numeric_df.clip(lower=0)
        
        normalized_df = np.log2(numeric_df + 1)
        return normalized_df

    def export_dataset(self, df: pd.DataFrame, dataset_name: str, output_dir: str = "data/processed") -> dict:
        """Saves the normalized dataset and returns summary stats."""
        out_path = Path(output_dir)
        out_path.mkdir(parents=True, exist_ok=True)

        file_destination = out_path / f"{dataset_name}_normalized.csv"
        logger.info(f"Exporting processed dataset to {file_destination}...")
        df.to_csv(file_destination)

        summary = {
            "dataset_name": dataset_name,
            "gene_count": int(df.shape[0]),
            "sample_count": int(df.shape[1]),
            "output_path": str(file_destination),
            "mean_expression": float(df.values.mean()),
        }
        logger.info(f"Export complete. Samples: {summary['sample_count']}, Genes: {summary['gene_count']}")
        return summary
