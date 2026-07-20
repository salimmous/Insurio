---
name: corbell
description: Use Corbell AI to scan, graph, and query the Insurio architecture map.
---

# Corbell AI Architecture Mapper

This skill integrates Corbell AI into the Insurio project to automatically map, document, and validate changes to the system architecture.

## Installation

Ensure Corbell is installed on your local Python environment:
```bash
pip install corbell
```

## Setup & Scanning

To initialize Corbell in the Insurio project:
```bash
corbell init
```
This generates the local workspace database and starts scanning files for:
- Database tables and relations
- Livewire and Blade UI routes
- Model connections and events

## Actions & Workflow

1. **Serve UI**:
   Launch the visual architecture mapping interface locally:
   ```bash
   corbell ui serve
   ```
   Open `http://localhost:7433` in your browser to view call paths and service interactions.

2. **Generate Specifications**:
   Create high-level design documents (HLD/LLD) matching current patterns:
   ```bash
   corbell spec new --prd "feature_name"
   ```
