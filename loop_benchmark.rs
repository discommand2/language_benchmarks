use std::sync::atomic::{AtomicUsize, Ordering};
use std::sync::Arc;
use std::io::{self, Write, Error};
use signal_hook::{iterator::Signals, consts::SIGTERM};
use rustc_version::{version};

fn main() -> Result<(), Error> {
    let count_loops = Arc::new(AtomicUsize::new(0));
    let count_loops_clone = Arc::clone(&count_loops);

    let mut signals = Signals::new(&[SIGTERM])?;

    std::thread::spawn(move || {
        for _ in signals.forever() {
            println!("Rust {} executed the loop {} times before termination.",
                     version().unwrap(),
                     count_loops_clone.load(Ordering::Relaxed));
            io::stdout().flush().unwrap();
            std::process::exit(0);
        }
    });

    loop {
        count_loops.fetch_add(1, Ordering::Relaxed);
    }
}